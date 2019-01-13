<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * Class UserController.
 */
class UserController extends AbstractController
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService, FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
        $this->userService = $userService;
    }

    /**
     * @Route("/registration", methods={"GET", "POST"}, name="app_registration")
     */
    public function registrationAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/registration.html.twig', [
          'user_registration' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile", methods={"GET", "POST"}, name="app_profile")
     */
    public function profileAction()
    {
        /** @var User $user */
        $user = $this->getUser();
#        $count_articles = count($user->getArticles());

        if ($user) {
            return $this->render('user/profile.html.twig', [
              'user' => $user,
#              'count_articles' => $count_articles,
            ]);
        } else {
            $this->flashBag->add('error', 'User is not logged in');

            return $this->redirectToRoute('app_login');
        }
    }
}
