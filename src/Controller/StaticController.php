<?php

namespace App\Controller;

use App\Entity\Message;
use App\Helpers\DefaultTrait;
use App\Manager\EntityManager;
use App\Repository\AboutUsRepository;
use App\Repository\CategoryRepository;
use App\Repository\ConfigurationRepository;
use App\Repository\ContactUsRepository;
use App\Repository\MessageRepository;
use App\Repository\SocialNetworkRepository;
use App\Repository\TermsConditionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/static")
 */
class StaticController extends AbstractController
{
    use DefaultTrait;

    /**
     * @Route("/{page}", name="app_static", methods={"GET"})
     */
    public function index(
        AboutUsRepository $aboutUsRepository,
        TermsConditionsRepository $termsConditionsRepository,
        ContactUsRepository $contactUsRepository,
        SocialNetworkRepository $networkRepository,
        ConfigurationRepository $configurationRepository,
        string $page
    ): Response {

        if (!in_array($page, ["about-us", "terms-conditions", "contact-us", "social-network", "features"])) {
            throw $this->createNotFoundException("Page not found");
        }

        $response = [];

        switch ($page) {
            case 'about-us':
                $response['data'] = $aboutUsRepository->findAboutUsDescription();
                break;
            case 'terms-conditions':
                $response['data'] = $termsConditionsRepository->findTermsConditionDescription();
                break;
            case 'contact-us':
                $response = $contactUsRepository->findContact();
                break;
            case 'social-network':
                $response = $networkRepository->findSocialNetwork();
                break;
            default:
                $response = $configurationRepository->findConfiguration();
                break;
        }

        return $this->json($response);
    }

    /**
     * @Route("/contact/send-email", name="app_static_send_email", methods={"POST"})
     */
    public function sendEmailContact(
        Request $request,
        ValidatorInterface $validator,
        EntityManager $manager,
        MessageRepository $repository
    ): Response {

        if ($repository->checkExist($request->get('email'))) {
            return $this->json(['message' => 'Wait 5 minutes', 'path' => ''], 422);
        }

        $entity = new Message($request);

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

    /**
     * @Route("/mobile/menu/item", name="app_static_mobile_menu_item", methods={"GET"})
     */
    public function mobileMenuItem(CategoryRepository $repository): Response
    {
        return $this->json($this->menuItem($repository));
    }
}
