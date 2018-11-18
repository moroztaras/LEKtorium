<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController.
 */
class UserController extends AbstractController
{
    /**
     * @Route("/registration", methods={"GET", "POST"}, name="app_registration")
     */
    public function registrationAction(Request $request, UserService $userService)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userService->save($user);
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/registration.html.twig', [
          'user_registration' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin", methods={"GET"}, name="app_admin")
     */
    public function adminAction()
    {
        return $this->render('base.html.twig', [
          'message' => 'Welcome admin page!',
        ]);
    }
}
