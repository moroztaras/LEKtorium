<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class BlockController.
 */
class BlockController extends Controller
{
    public function logo(){
        return $this->render('block/logo.html.twig');
    }

    public function mainMenu(){
        return $this->render('block/main-menu.html.twig');
    }
//
//    public function mainMenuFooter(){
//        return $this->render('block/main-menu-footer.html.twig');
//    }
}
