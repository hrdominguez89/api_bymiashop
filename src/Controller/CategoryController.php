<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/{slug}", name="app_category", methods={"GET"})
     */
    public function index(CategoryRepository $repository, string $slug): Response
    {
        return $this->json($repository->findOneBySlug($slug)->asArray());
    }

    /**
     * @Route("/filter/{filter}", name="app_category_filter", methods={"GET"})
     */
    public function filter(CategoryRepository $repository, string $filter): Response
    {
        $categories = [];
        /** @var Category $item */
        foreach ($repository->filterCategory($filter) as $item){
            $categories[] = $item->asArray();
        }

        return $this->json($categories);
    }
}
