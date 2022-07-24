<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Helpers\ProductTrait;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\SpecificationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    use ProductTrait;

    /**
     * @Route("/filter{list}", name="app_product", methods={"POST"}, defaults={"list"="plist"})
     */
    public function filter(
        Request $request,
        ProductRepository $repository,
        BrandRepository $brandRepository,
        CategoryRepository $categoryRepository,
        SpecificationRepository $specificationRepository,
        $list
    ): Response {
        $pagination = $repository->filterProducts($request);

        $items = [];
        /** @var Product $item */
        foreach ($pagination->getItems() as $item) {
            $items[] = $item->asArray();
        }

        $ctg = [];
        /** @var Category $category */
        foreach ($categoryRepository->findCategories() as $category) {
            $ctg[] = $category->asArray2();
        }

        [$brands, $colors, $limit, $filterValues] = [
            $brandRepository->findBrands(),
            $specificationRepository->findColorSpecifications(),
            $request->get('limit', 12),
            $this->getFilterValues($request),
        ];
        $response = $list == 'plist' ? $items : $this->getProductsList(
            $pagination,
            $items,
            $brands,
            $ctg,
            $colors,
            $filterValues,
            $limit
        );

        return $this->json($response);
    }

    /**
     * @Route("/show/{slug}/{id}/entity", name="app_product_show", methods={"GET","POST"})
     * @Entity("product", expr="repository.findOneBySlugId(slug, id)")
     */
    public function show(ProductRepository $repository, Product $product): Response
    {
        [$rShip, $cFields] = [$repository->findRelationship($product->getId(), $product->getParentId()), []];

        foreach ($product->getAllSpecifications() as $sp) {
            $cFields[] = $sp;
        }

        /** @var Product $item */
        foreach ($rShip as $item) {

            // save price to calculate range price //
            //$pRange[] = $item->calcPrice();

            foreach ($item->getAllSpecifications() as $sp) {
                $cFields[] = $sp;
            }
        }

        $response = $product->asArray(true, $this->combineAllSpecifications($cFields));

        // select range //
        //$response['price'] = $repo->getRangePrice($pRange);

        return $this->json($response);
    }


    /**
     * @Route("/similar/{id}/entity", name="app_product_similar", methods={"GET"})
     * @Entity("p", expr="repository.findOneByAttr(id)")
     */
    public function similar(Request $request, ProductRepository $repository, Product $p): Response
    {
        $pagination = $repository->findSimilar($p->getId(), $p->getParentId(), $request->get('limit', 12));

        $response = [];
        /** @var Product $item */
        foreach ($pagination as $item) {
            $response[] = $item->asArray(false);
        }

        return $this->json($response);
    }

    /**
     * @Route("/{slug}/suggestions", name="app_product_suggestions", methods={"POST"})
     */
    public function suggestions(Request $request, ProductRepository $repository, $slug): Response
    {
        $pagination = $repository->suggestionsProducts($request->get('name', ''), $request->get('limit', 5), $slug);

        $response = [];
        /** @var Product $item */
        foreach ($pagination as $item) {
            $response[] = $item->asArray();
        }

        return $this->json($response);
    }
}
