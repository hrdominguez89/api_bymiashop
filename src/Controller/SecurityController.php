<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

/**
 * @Route("/security")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_security_login", methods={"POST"})
     */
    public function index(CustomerRepository $repository, $jwtKey): Response
    {
        $user = $this->getUser();

        $customer = $repository->findOneBy(['email' => $user->getUserIdentifier()]);

        return $this->json([
            'token' => JWT::encode($customer->asArray(), $jwtKey),
        ]);
    }
}
