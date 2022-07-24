<?php

namespace App\Controller\Secure;

use App\Entity\Customer;
use App\Entity\FavoriteProduct;
use App\Entity\Product;
use App\Manager\EntityManager;
use App\Repository\FavoriteProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/wish")
 */
class WishController extends AbstractController
{
    /**
     * @Route("/index", name="app_wish_index", methods={"POST"})
     */
    public function index(
        EntityManager $manager,
        FavoriteProductRepository $repository,
        ValidatorInterface $validator
    ): Response {
        /** @var Customer $user */
        $user = $this->getUser();

        $wlImport = json_decode(file_get_contents("php://input"), true);
        $wlImport = $wlImport['wl_import'] ?? [];

        if (count($wlImport) > 0) {
            $newData = $repository->findNewProduct($repository->getIds($wlImport));

            /** @var Product $datum */
            foreach ($newData as $datum) {

                $entity = new FavoriteProduct($user, $datum);

                $errors = $validator->validate($entity);
                if (count($errors) == 0) {
                    // The entity already exists.
                    $manager->save($entity);
                }
            }
        }

        $response = [];
        /** @var FavoriteProduct[] $userFavorites */
        $userFavorites = $repository->findByUid($user->getId());
        foreach ($userFavorites as $item) {
            $response[] = $item->getProductId()->asArray(false);
        }

        return $this->json($response);
    }

    /**
     * @Route("/add/{id}", name="app_wish_add", methods={"POST"})
     */
    public function add(Product $product, EntityManager $manager, FavoriteProductRepository $repository): Response
    {
        /** @var Customer $user */
        $user = $this->getUser();

        $entity = $repository->findOneBy(['productId' => $product, 'customerId' => $user]);
        if (!$entity) {
            $entity = new FavoriteProduct($user, $product);
            $manager->save($entity);
        }

        return $this->json(['OK']);
    }

    /**
     * @Route("/remove/{id}", name="app_wish_delete", methods={"DELETE"})
     */
    public function remove(Product $product, EntityManager $manager, FavoriteProductRepository $repository): Response
    {
        /** @var Customer $user */
        $user = $this->getUser();

        $entity = $repository->findOneBy(['productId' => $product, 'customerId' => $user]);
        if ($entity) {
            $manager->remove($entity);
        }

        return $this->json(['OK']);
    }

    /**
     * @Route("/clear-list", name="app_clear_list", methods={"DELETE"})
     */
    public function clearList(EntityManager $manager): Response
    {
        /** @var Customer $user */
        $user = $this->getUser();

        foreach ($user->getFavoriteProducts() as $entity) {
            $manager->remove($entity);
        }

        return $this->json(['OK']);
    }
}
