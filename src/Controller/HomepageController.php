<?php

namespace App\Controller;

use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{


    /**
     * @param TricksRepository $tricksRepository parameter
     *
     * @return Response
     *
     * @Route("/", name="index")
     */
    public function index(TricksRepository $tricksRepository): Response
    {

        $tricks = $tricksRepository->findBy(['deleted_at' => null]);

        return $this->render(
            'homepage/index.html.twig', [
                'tricks' => $tricks
            ]
        );
    }


}
