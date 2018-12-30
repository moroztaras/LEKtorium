<?php

namespace App\Controller\Admin;

use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\User;
use App\AppEvents;
use App\Event\UserEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class UserController.
 *
 * @Route("/admin/user")
 */
class UserController extends Controller
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("", methods={"GET"}, name="admin_user_list")
     */
    public function listAction(Request $request)
    {
        $users = $this->userService->list($request);

        return $this->render('admin/user/list.html.twig', [
          'users' => $users,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="admin_user_delete", requirements={"id": "\d+"})
     * @Method({"GET", "DELETE"})
     */
    public function deleteAction($id)
    {
        /** @var User $user */
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $this->userService->remove($user);

        $dispatcher = $this->get('event_dispatcher');
        $event = new UserEvent($user);
        $dispatcher->dispatch(AppEvents::USER_DELETE, $event);

        return $this->redirectToRoute('admin_user_list');
    }
}
