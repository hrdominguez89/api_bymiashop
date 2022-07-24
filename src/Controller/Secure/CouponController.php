<?php

namespace App\Controller\Secure;

use App\Entity\CouponDiscount;
use App\Entity\Customer;
use App\Entity\CustomerCouponDiscount;
use App\Manager\EntityManager;
use Firebase\JWT\JWT;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/coupon")
 */
class CouponController extends AbstractController
{

    public function index()
    {

    }

    /**
     * @Route("/{nro}/apply", name="app_coupon_apply")
     * @Entity("couponDiscount", expr="repository.findOneByNro(nro)")
     * @Security("is_granted('CREATE', couponDiscount)")
     */
    public function apply(EntityManager $manager, CouponDiscount $couponDiscount, $jwtKey): Response
    {
        /** @var Customer $user */
        $user = $this->getUser();

        $entity = new CustomerCouponDiscount($user, $couponDiscount);

        $manager
            ->persist($couponDiscount->setNumberOfUses($couponDiscount->getNumberOfUses() - 1))
            ->persist($entity)
            ->persist($user)
            ->flush();

        return $this->json([
            'token' => JWT::encode($user->asArray(), $jwtKey),
        ]);
    }
}
