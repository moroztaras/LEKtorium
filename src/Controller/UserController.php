<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Services\UserService;
use App\AppEvents;
use App\Event\UserEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController.
 */
class UserController extends Controller
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

            $dispatcher = $this->get('event_dispatcher');
            $event = new UserEvent($user);
            $dispatcher->dispatch(AppEvents::USER_CREATED,$event);

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
