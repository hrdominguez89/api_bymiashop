<?php

namespace App\Controller\Secure;

use App\Entity\Customer;
use App\Entity\Product;
use App\Entity\Shopping;
use App\Helpers\ShoppingTrait;
use App\Manager\EntityManager;
use App\Repository\ShoppingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/shopping")
 */
class ShoppingController extends AbstractController
{
    use ShoppingTrait;

    /**
     * @Route("/index", name="app_shopping_index", methods={"POST"})
     */
    public function index(ShoppingRepository $repository, EntityManager $manager): Response
    {
        /** @var Customer $user */
        $user = $this->getUser();

        $shImport = json_decode(file_get_contents("php://input"), true);
        $shImport = $shImport['sh_import'] ?? [];

        if (count($shImport) > 0) {
            // ids products to import
            [$ids, $oldIds] = [$this->getIds($shImport), []];

            /** @var Shopping[] $entities */
            $entities = $repository->findByUidIds($user->getId(), $ids);

            // update old shopping cart data
            foreach ($entities as $entity) {
                $manager->save(
                    $entity->setQuantity(
                        $this->getQuantity($entity->getProductId()->getId(), $shImport, $entity->getQuantity())
                    )
                );
                $oldIds[] = $entity->getProductId()->getId();
            }

            // save new shopping cart data
            $newData = $repository->findNewProduct($repository->getNewIds($ids, $oldIds));

            /** @var Product $datum */
            foreach ($newData as $datum) {
                $manager->save(new Shopping($user, $datum, $this->getQuantity($datum->getId(), $shImport)));
            }
        }

        // return all shopping data
        $response = [];
        $userShopping = $repository->findByUid($user->getId());
        foreach ($userShopping as $item) {
            $response[] = $item->asArray();
        }

        return $this->json($response);
    }

    /**
     * @Route("/add/{id}", name="app_shopping_add", methods={"POST"})
     */
    public function add(Request $request, ShoppingRepository $repo, EntityManager $manager, Product $product): Response
    {
        /** @var Customer $user */
        $user = $this->getUser();

        $entity = $repo->findOneBy(['productId' => $product, 'customerId' => $user]);

        $qty = $request->get('quantity', 1);
        $qty = ($qty > 0 ? $qty : 1);

        $manager->save(
            $entity
                ? $entity->setQuantity($entity->getQuantity() + $qty)
                : new Shopping($user, $product, $qty)
        );

        return $this->json(['ok']);
    }

    /**
     * @Route("/edit", name="app_shopping_edit", methods={"PUT"})
     */
    public function edit(
        ShoppingRepository $repository,
        EntityManager $manager
    ): Response {
        /** @var Customer $user */
        $user = $this->getUser();

        $shUpdate = json_decode(file_get_contents("php://input"), true);
        $shUpdate = $shUpdate['sh_update'] ?? [];

        if (count($shUpdate) > 0) {
            /** @var Shopping[] $entities */
            $entities = $repository->findByUidIds($user->getId(), $this->getIds($shUpdate));

            // update old shopping cart data
            foreach ($entities as $entity) {
                $manager->save(
                    $entity->setQuantity(
                        $this->getQuantity($entity->getProductId()->getId(), $shUpdate, $entity->getQuantity())
                    )
                );
            }
        }

        return $this->json(['ok']);
    }

    /**
     * @Route("/remove/{id}", name="app_shopping_remove", methods={"DELETE"})
     */
    public function remove(Product $product, ShoppingRepository $repository, EntityManager $manager): Response
    {
        /** @var Customer $user */
        $user = $this->getUser();

        $entity = $repository->findOneBy(['productId' => $product, 'customerId' => $user]);

        if ($entity) {
            $manager->remove($entity);
        }

        return $this->json(['ok']);
    }

    /**
     * @Route("/clear-list", name="app_shopping_clear", methods={"DELETE"})
     */
    public function clearList(EntityManager $manager, ShoppingRepository $repository): Response
    {
        /** @var Customer $user */
        $user = $this->getUser();

        /** @var Shopping[] $entities */
        $entities = $repository->findByUid($user->getId());
        foreach ($entities as $entity) {
            $manager->remove($entity);
        }

        return $this->json(['ok']);
    }
}
