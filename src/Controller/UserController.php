<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
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
     * @param Request $request
     * @Route("/register", methods={"GET", "POST"}, name="user_register")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userRegisterAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute("list_articles");
        }

        return $this->render('user/register.html.twig', [
          'user_register' => $form->createView(),
        ]);
    }
}
