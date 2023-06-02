<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class Tricks extends AbstractController{

    /**
     * @Route("/tricks", name="tricksList")
     */
    public function tricksList(){

        return $this->render('tricks/list.html.twig', [
            'title' => 'Tricks'
        ]);
    }
}