<?php

namespace App\Controller\Secure;

use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\OrderEmail;
use App\Entity\PayPal;
use App\Helpers\PayPalTrait;
use App\Manager\EntityManager;
use App\Repository\CustomerCouponDiscountRepository;
use App\Repository\ShoppingRepository;
use Firebase\JWT\JWT;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/paypal")
 */
class PaypalController extends AbstractController
{
    use PayPalTrait;

    /**
     * @Route("/index", name="app_paypal_index", methods={"GET"})
     * @Entity("payPal", expr="repository.findPaypal()")
     */
    public function index(PayPal $payPal): Response
    {
        return $this->json(['secret' => $payPal->isSandBox() ? $payPal->getClientIdSandBox() : $payPal->getClientId()]);
    }

    /**
     * @Route("/create-order", name="app_paypal_create_order", methods={"POST"})
     * @Entity("payPal", expr="repository.findPaypal()")
     */
    public function createOrder(
        Request $request,
        ShoppingRepository $repository,
        ValidatorInterface $validator,
        EntityManager $manager,
        PayPal $payPal
    ): Response {

        /** @var Customer $user */
        $user = $this->getUser();

        $o = new Order($request, $user, PayPal::NAME);

        // check errors
        $er = $validator->validate($o);
        if (count($er) > 0) {
            return $this->json(['msg' => $er->get(0)->getMessage(), 'p' => $er->get(0)->getPropertyPath()], 422);
        }

        $rsp = ($this->client($payPal))->execute($this->ordersCreateRequest($manager, $repository, $user, $o));

        /** @var \stdClass $result */
        $result = $rsp->result;

        $manager->save($o->setCheckoutStatus($result->status)->setCheckoutId($result->id));

        return $this->json($rsp->result);
    }

    /**
     * @Route("/on-aprove/{id}/order", name="app_paypal_on_approve", methods={"POST"})
     * @Entity("order", expr="repository.findOneById(id)")
     * @Security("is_granted('READ', order)")
     */
    public function onApprove(EntityManager $manager, Order $order): Response
    {
        $manager->save($order->setCheckoutStatus(PayPal::APPROVED));

        return $this->json(['ok']);
    }

    /**
     * @Route("/on-client-authorization/{id}/order", name="app_paypal_on_client_authorization", methods={"POST"})
     * @Entity("order", expr="repository.findOneById(id)")
     * @Security("is_granted('READ', order)")
     */
    public function onClientAuthorization(
        CustomerCouponDiscountRepository $couponDiscountRepository,
        EntityManager $manager,
        Order $order,
        $jwtKey
    ): Response {

        /** @var Customer $user */
        $user = $this->getUser();

        if ($couponDiscount = $couponDiscountRepository->findOneByCustomerId($user->getId())) {
            $manager->persist($couponDiscount->setApplied(true))->persist($user);
        }

        $manager->persist($order->setCheckoutStatus(PayPal::COMPLETED)->setStatus('PENDING'))->flush();

        $orderEmail = new OrderEmail($order);
        $manager->save($orderEmail);

        return $this->json([
            'token' => JWT::encode($user->asArray(), $jwtKey),
        ]);
    }

    /**
     * @Route("/on-{status}/{id}/order", name="app_paypal_on_cancel_error_url", methods={"POST"})
     * @Entity("order", expr="repository.findOneById(id)")
     * @Security("is_granted('READ', order)")
     */
    public function onCancelError(EntityManager $manager, Order $order, $status): Response
    {
        $manager->save($order->setCheckoutStatus(strtoupper($status)));

        return $this->json(['ok']);
    }

}
