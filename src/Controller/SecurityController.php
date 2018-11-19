<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController.
 */
class SecurityController extends AbstractController
{
//    /**
//     * @Route("/login",  methods={"GET", "POST"}, name="app_login")
//     */
//    public function login(AuthenticationUtils $authenticationUtils): Response
//    {
//        // get the login error if there is one
//        $error = $authenticationUtils->getLastAuthenticationError();
//        // last username entered by the user
//        $lastUsername = $authenticationUtils->getLastUsername();
//
//        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
//    }

    /**
     * @Route("/login", name="app_login")
     */
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {
        $user = new User();
        $user->setEmail($authenticationUtils->getLastUsername());

        $form = $this->createForm(LoginType::class, $user, [
          'action' => $this->generateUrl('login_check'),
        ]);

        if ($error = $authenticationUtils->getLastAuthenticationError()) {
            $this->addFlash('message', $error->getMessage());
        }

        return $this->render('security/login.html.twig', [
          'form' => $form->createView(),
        ]);
    }
}
