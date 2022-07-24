<?php

namespace App\Helpers;

use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\OrderItems;
use App\Entity\PayPal;
use App\Manager\EntityManager as eManager;
use App\Repository\ShoppingRepository as sRepo;
use PayPalCheckoutSdk\Core\PayPalEnvironment;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

trait PayPalTrait
{
    /**
     * Returns PayPal HTTP client instance with environment which has access
     * credentials context. This can be used invoke PayPal API's provided the
     * credentials have the access to do so.
     */
    public function client(PayPal $payPal): PayPalHttpClient
    {
        return new PayPalHttpClient($this->environment($payPal));
    }

    /**
     * @param eManager $m
     * @param sRepo $r
     * @param Customer $c
     * @param Order $o
     * @return OrdersCreateRequest
     */
    public function ordersCreateRequest(eManager $m, sRepo $r, Customer $c, Order $o): OrdersCreateRequest
    {
        $createRequest = new OrdersCreateRequest();
        $createRequest->prefer('return=representation');
        $createRequest->body = $this->buildRequestBody($m, $r, $c, $o);

        return $createRequest;
    }

    /**
     * Setting up the JSON request body for creating the Order. The Intent in the
     * request body should be set as "CAPTURE" for capture intent flow.
     *
     */
    private function buildRequestBody(eManager $m, sRepo $r, Customer $c, Order $o, $t = false): array
    {
        if ($t) {
            return $this->buildPaypalRequestBody();
        }

        [$items, $quantity, $subTotal] = [[], 0, 0.00];
        $userShopping = $r->findByUid($c->getId());

        foreach ($userShopping as $s) {
            $p = $s->getProductId();
            if ($p->getAvailability() == 'in-stock' && $p->getStock() >= $s->getQuantity()) {

                $items[] = $s->asArray(true);

                $quantity = $quantity + $s->getQuantity();
                $subTotal = $subTotal + ($p->calcPrice() * $s->getQuantity());

                $oItem = new OrderItems($o, $p, $s);
                $m->persist($oItem);
            }
        }

        $m->flush();

        [$taxTotal, $shipping, $handling, $insurance, $shDiscount] = [0.00, 0.00, 0.00, 0.00, 0.00];

        // Should equal item_total + tax_total + shipping + handling + insurance - shipping_discount - discount
        $total = ($subTotal + $taxTotal + $shipping + $handling + $insurance) - $shDiscount;

        $discount = $c->getDiscount($total);

        $total = $total - $discount;

        $o->setSubTotal($subTotal)->setTotal($total)->setQuantity($quantity)->setDiscount($discount);

        return [
            'intent' => 'CAPTURE',
            'application_context' => [
                'brand_name' => 'BYMIA',
                'locale' => 'es-MX',
                'landing_page' => 'BILLING',
                'user_action' => 'PAY_NOW',
            ],
            'purchase_units' => [
                [
                    'custom_id' => 'cu-'.$c->getId(),
                    'reference_id' => 'ref-'.$o->getId(),
                    'description' => 'Compra de productos en BYMIA',
                    'soft_descriptor' => 'Bymia',
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $total,
                        'breakdown' =>
                            [
                                'item_total' => [
                                    'currency_code' => 'USD',
                                    'value' => $subTotal,
                                ],
                                'shipping' => [
                                    'currency_code' => 'USD',
                                    'value' => '0.00',
                                ],
                                'handling' => [
                                    'currency_code' => 'USD',
                                    'value' => '0.00',
                                ],
                                'tax_total' => [
                                    'currency_code' => 'USD',
                                    'value' => '0.00',
                                ],
                                'shipping_discount' => [
                                    'currency_code' => 'USD',
                                    'value' => '0.00',
                                ],
                                'discount' => [
                                    'currency_code' => 'USD',
                                    'value' => $discount,
                                ],
                            ],
                    ],
                    'items' => $items,
                    'shipping' => [
                        'name' => [
                            'full_name' => $o->getCheckoutShippingFirstName().' '.$o->getCheckoutShippingLastName(),
                        ],
                        'address' => [
                            'address_line_1' => $o->getCheckoutShippingAddress(),
                            'address_line_2' => $o->getCheckoutBillingStreetAddress(),
                            'admin_area_2' => $o->getCheckoutShippingCity(),
                            'admin_area_1' => $o->getCheckoutShippingState(),
                            'postal_code' => $o->getCheckoutShippingPostcode(),
                            'country_code' => $o->getCheckoutShippingCountryIso(),
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    private function buildPaypalRequestBody(): array
    {
        return [
            'intent' => 'CAPTURE',
            'application_context' => [
                'brand_name' => 'EXAMPLE INC',
                'locale' => 'en-US',
                'landing_page' => 'BILLING',
                'shipping_preference' => 'SET_PROVIDED_ADDRESS',
                'user_action' => 'PAY_NOW',
            ],
            'purchase_units' => [
                [
                    'reference_id' => 'PUHF',
                    'description' => 'Sporting Goods',
                    'custom_id' => 'CUST-HighFashions',
                    'soft_descriptor' => 'HighFashions',
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => '220.00',
                        'breakdown' =>
                            array(
                                'item_total' =>
                                    array(
                                        'currency_code' => 'USD',
                                        'value' => '180.00',
                                    ),
                                'shipping' =>
                                    array(
                                        'currency_code' => 'USD',
                                        'value' => '20.00',
                                    ),
                                'handling' =>
                                    array(
                                        'currency_code' => 'USD',
                                        'value' => '10.00',
                                    ),
                                'tax_total' =>
                                    array(
                                        'currency_code' => 'USD',
                                        'value' => '20.00',
                                    ),
                                'shipping_discount' =>
                                    array(
                                        'currency_code' => 'USD',
                                        'value' => '10.00',
                                    ),
                            ),
                    ],
                    'items' =>
                        array(
                            array(
                                'name' => 'T-Shirt',
                                'description' => 'Green XL',
                                'sku' => 'sku01',
                                'unit_amount' =>
                                    array(
                                        'currency_code' => 'USD',
                                        'value' => '90.00',
                                    ),
                                'tax' =>
                                    array(
                                        'currency_code' => 'USD',
                                        'value' => '10.00',
                                    ),
                                'quantity' => '1',
                                'category' => 'PHYSICAL_GOODS',
                            ),
                            array(
                                'name' => 'Shoes',
                                'description' => 'Running, Size 10.5',
                                'sku' => 'sku02',
                                'unit_amount' =>
                                    array(
                                        'currency_code' => 'USD',
                                        'value' => '45.00',
                                    ),
                                'tax' =>
                                    array(
                                        'currency_code' => 'USD',
                                        'value' => '5.00',
                                    ),
                                'quantity' => '2',
                                'category' => 'PHYSICAL_GOODS',
                            ),
                        ),
                    'shipping' =>
                        array(
                            'method' => 'United States Postal Service',
                            'name' =>
                                array(
                                    'full_name' => 'John Doe',
                                ),
                            'address' =>
                                array(
                                    'address_line_1' => '123 Townsend St',
                                    'address_line_2' => 'Floor 6',
                                    'admin_area_2' => 'San Francisco',
                                    'admin_area_1' => 'CA',
                                    'postal_code' => '94107',
                                    'country_code' => 'US',
                                ),
                        ),
                ],
            ],
        ];
    }

    /**
     * Setting up and Returns PayPal SDK environment with PayPal Access credentials.
     * For demo purpose, we are using SandboxEnvironment. In production this will be
     * ProductionEnvironment.
     */
    public function environment(PayPal $payPal): PayPalEnvironment
    {
        $isSandBox = $payPal->isSandBox();

        $clientId = $isSandBox ? $payPal->getClientIdSandBox() : $payPal->getClientId();
        $clientSecret = $isSandBox ? $payPal->getClientSecretSandBox() : $payPal->getClientSecret();

        return $isSandBox
            ? new SandboxEnvironment($clientId, $clientSecret)
            : new ProductionEnvironment($clientId, $clientSecret);
    }

}
