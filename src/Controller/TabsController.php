<?php

namespace App\Controller;

use App\Entity\Product;
use App\Helpers\TabsTrait;
use App\Repository\ProductReviewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tabs")
 */
class TabsController extends AbstractController
{
    use TabsTrait;

    /**
     * @Route("/{id}/reviews", name="app_tabs_reviews", methods={"POST"})
     */
    public function reviews(Request $request, ProductReviewsRepository $repository, Product $product): Response
    {
        [$page, $limit] = [$request->get('page', 1), $request->get('limit', 5)];

        $pagination = $repository->findReviews($product->getId(), $page, $limit);

        return $this->json($this->getListReviews($pagination, $page, $limit));
    }
}
