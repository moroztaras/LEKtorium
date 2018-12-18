<?php

namespace App\Controller;

use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class CommentController.
 */
class CommentController extends Controller
{
    public function form()
    {
        $form = $this->createForm(CommentType::class);

        return $this->render('comment/form.html.twig', [
          'form_comment' => $form->createView(),
        ]);
    }
}
