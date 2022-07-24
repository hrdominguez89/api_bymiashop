<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Helpers\OrderTrait;
use App\Manager\EntityManager;
use App\Repository\AdvertisementsRepository;
use App\Repository\BrandRepository;
use App\Repository\CoverImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DefaultController extends AbstractController
{
    use OrderTrait;

    /**
     * @Route("/index", name="app_default_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/banner", name="app_banner", methods={"GET"})
     */
    public function banner(CoverImageRepository $repository): Response
    {
        return $this->json($repository->findCoverImage());
    }

    /**
     * @Route("/brands", name="app_brands", methods={"GET"})
     */
    public function brands(BrandRepository $repository): Response
    {
        return $this->json($repository->findBrands());
    }

    /**
     * @Route("/advertisements", name="app_advertisements", methods={"GET"})
     */
    public function advertisements(AdvertisementsRepository $repository): Response
    {
        return $this->json($repository->findAdvertisements());
    }

    /**
     * @Route("/news-letter", name="app_news_letter", methods={"POST"})
     */
    public function newsLetter(Request $request, ValidatorInterface $validator, EntityManager $manager): Response
    {
        $entity = new Newsletter();
        $entity->setEmail($request->get('email'));

        // check errors
        $er = $validator->validate($entity);
        if (count($er) > 0) {
            return $this->json(['msg' => $er->get(0)->getMessage(), 'p' => $er->get(0)->getPropertyPath()], 422);
        }

        $manager->save($entity);

        return $this->json(['ok']);
    }
}
