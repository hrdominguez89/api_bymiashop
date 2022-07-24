<?php

namespace App\Controller\Secure;

use App\Entity\Customer;
use App\Entity\Order;
use App\Helpers\OrderTrait;
use App\Repository\ContactUsRepository;
use App\Repository\OrderRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/orders")
 */
class OrderController extends AbstractController
{
    use OrderTrait;

    /**
     * @Route("/index", name="app_order", methods={"POST"})
     */
    public function index(Request $request, OrderRepository $repository): Response
    {
        /** @var Customer $user */
        $user = $this->getUser();

        [$page, $limit, $query] = [
            $request->get('page', 1),
            $request->get('limit', 5),
            $request->get('query'),
        ];

        $pagination = $repository->findAllPartial($user->getId(), $page, $limit, $query);

        return $this->json($this->getListOrders($pagination, $page, $limit));
    }

    /**
     * @Route("/show/{id}", name="app_order_show", methods={"POST"})
     * @Entity("order", expr="repository.findOneById(id)")
     * @Security("is_granted('READ', order)")
     */
    public function show(Order $order): Response
    {
        return $this->json($order->asArray());
    }

    /**
     * @Route("/download/{id}", name="app_order_download", methods={"GET"})
     * @Entity("order", expr="repository.findOneById(id)")
     * @Security("is_granted('READ', order)")
     */
    public function download(Pdf $pdf, ContactUsRepository $contactUsRepository, Order $order, $urlBack): Response
    {
        $asArray = $order->asArray();

        $html = $this->renderView('order/voucher.html.twig', [
            'order' => $asArray,
            'statusPayment' => $this->statusPayment($asArray['paymentMethod'], $asArray['checkoutStatus']),
            'statusOrder' => $this->statusOrder($asArray['status']),
            'urlBack' => $urlBack,
        ]);

        $footerHtml = $this->renderView('order/includes/footer.html.twig', [
            'contact' => $contactUsRepository->findContact(),
        ]);

        return new PdfResponse($pdf->getOutputFromHtml($html, ['footer-html' => $footerHtml]), 'voucher.pdf');

    }
}
