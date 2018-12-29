<?php

namespace App\Controller\Admin;

use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Article;
use App\Form\Admin\ArticleType;
use App\AppEvents;
use App\Event\ArticleEvent;
use App\Services\ArticleService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
}
