<?php

namespace App\Controller\Secure;

use App\Entity\Customer;
use App\Entity\Product;
use App\Entity\ProductReviews;
use App\Manager\EntityManager;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/customer")
 */
class CustomerController extends AbstractController
{
    /**
     * @Route("/index", name="app_customer", methods={"POST"})
     */
    public function index($jwtKey): Response
    {
        /** @var Customer $user */
        $user = $this->getUser();

        return $this->json([
            'token' => JWT::encode($user->asArray(), $jwtKey),
        ]);
    }

    /**
     * @Route("/send/{id}/review", name="app_tcustomer_send_review", methods={"POST"})
     */
    public function sendReview(
        Request $request,
        ValidatorInterface $validator,
        EntityManager $manager,
        Product $product
    ): Response {
        /** @var Customer $user */
        $user = $this->getUser();

        $entity = new ProductReviews($product, $user, $request);

        // check errors
        $errors = $validator->validate($entity);
        if (count($errors) > 0) {
            return $this->json(
                ['message' => $errors->get(0)->getMessage(), 'path' => $errors->get(0)->getPropertyPath()],
                422
            );
        }

        $manager->save($entity);

        return $this->json(['ok']);
    }
}
