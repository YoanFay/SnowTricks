<?php

namespace App\Controller;

use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class Homepage extends AbstractController{

    /**
     * @Route("/{tricksPage}", name="index")
     */
    public function index(TricksRepository $tricksRepository, $tricksPage = null){

        $tricks = $tricksRepository->findAll();

        if ($tricksPage === null){
            $title = "Accueil";
        }
        else{
            $title = "Tricks";
        }

        return $this->render('homepage/index.html.twig', [
            'title' => $title,
            'tricks' => $tricks,
            'tricksPage' => $tricksPage
        ]);
    }

}