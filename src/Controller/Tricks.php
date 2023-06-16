<?php

namespace App\Controller;

use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class Tricks extends AbstractController
{

    /**
     * @Route("/tricks/details/{slug}", name="tricksDetails")
     */
    public function tricksDetails(
        TricksRepository $tricksRepository,
        $slug
    )
    {

        $trick = $tricksRepository->findOneBy(['slug' => $slug]);

        return $this->render('tricks/details.html.twig', [
            'title' => 'Tricks',
            'trick' => $trick
        ]);
    }
}