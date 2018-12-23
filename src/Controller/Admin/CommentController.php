<?php

namespace App\Controller\Admin;

use App\Services\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController.
 *
 * @Route("/admin/comment")
 */
class CommentController extends Controller
{
    /**
     * @Route("", methods={"GET"}, name="admin_comment_list")
     */
    public function listAction(Request $request, CommentService $commentService)
    {
        $comments = $commentService->list($request);

        return $this->render('admin/comment/list.html.twig', [
          'comments' => $comments,
        ]);
    }
}
