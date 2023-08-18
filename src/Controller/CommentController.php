<?php

namespace App\Controller;

use App\Repository\CommentaireRepository;
use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment/{slug}/list", name="commentList")
     */
    public function commentList(
        CommentaireRepository $commentaireRepository,
        TricksRepository $tricksRepository,
        Request $request,
        $slug
    ): Response
    {
        $start = $request->request->get('start');
        $number = $request->request->get('number');

        $start = $start - 1;

        $trick = $tricksRepository->findOneBy(['slug' => $slug]);

        $commentaire = $commentaireRepository->findBetweenStartAndEnd($trick, $start, $number);

        return $this->render('comment/list.html.twig', [
            'controller_name' => 'CommentController',
            'commentaires' => $commentaire
        ]);
    }
}
