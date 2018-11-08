<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController.
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/login", methods={"GET", "POST"}, name="user_login")
     */
    public function loginAction()
    {
        $form = $this->createForm(LoginType::class);

        return $this->render('user/login.html.twig', [
          'user_login' => $form->createView(),
        ]);
    }

    /**
     * @Route("/registration", methods={"GET", "POST"}, name="user_registration")
     */
    public function registrationAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('list_articles');
        }

        return $this->render('user/register.html.twig', [
          'user_register' => $form->createView(),
        ]);
    }
}
