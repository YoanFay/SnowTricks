<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Images;
use App\Entity\Tricks as TricksEntity;
use App\Entity\Video;
use App\Form\CommentaireType;
use App\Form\Tricks\AddTricksType;
use App\Repository\CommentaireRepository;
use App\Repository\TricksRepository;
use App\Service\UploadService;
use App\Service\UtilitaireService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Tricks extends AbstractController
{

    /**
     * @Route("/tricks/details/{slug}", name="tricksDetails")
     */
    public function tricksDetails(
        TricksRepository $tricksRepository,
        CommentaireRepository $commentaireRepository,
        Request          $request,
                         $slug
    )
    {

        $trick = $tricksRepository->findOneBy(['slug' => $slug, 'deleted_at' => null]);

        if (!$trick) {
            throw $this->createNotFoundException();
        }

        $commentaire = new Commentaire();

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();

            $commentaire->setCreatedAt(new \DateTime());
            $commentaire->setTricks($trick);
            $commentaire->setUser($this->getUser());

            $em->persist($commentaire);
            $em->flush();

            return $this->redirectToRoute('tricksDetails', ['slug' => $slug]);

        }

        $tricksCommentaires = $commentaireRepository->findBy(['tricks' => $trick], ['createdAt' => 'DESC']);

        return $this->render('tricks/details.html.twig', [
            'title' => 'Tricks',
            'trick' => $trick,
            'commentaires' => $tricksCommentaires,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/tricks/ajouter", name="tricksAdd")
     */
    public function tricksAdd(TricksRepository $tricksRepository, Request $request, UtilitaireService $utilitaireService, UploadService $uploadService)
    {

        if (!$this->isGranted('ROLE_USER')) {
            $this->addFlash('error', "Vous n'avez pas accès à cette page");
            return $this->redirectToRoute('index');
        }

        $trick = new TricksEntity();

        $form = $this->createForm(AddTricksType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $trick->setSlug($utilitaireService->makeSlug($trick->getName()));
            $trick->setUser($this->getUser());

            $em->persist($trick);

            $images = $request->files->get('add_tricks')['images'];
            $authorizedExt = ['png', 'jpg', 'jpeg', 'webp', 'avif', 'svg'];

            foreach ($images as $image) {
                if (in_array($image->getClientOriginalExtension(), $authorizedExt) && $image->getSize() <= 500000) {
                    if ($name = $uploadService->uploadTricks($trick->getSlug(), $image)) {
                        $imageEntity = new Images();

                        $imageEntity->setTricks($trick);
                        $imageEntity->setName($name);
                        $imageEntity->setType($image->getClientOriginalExtension());
                        $em->persist($imageEntity);
                    }
                }
            }

            foreach ($request->request->get('add_tricks')['videos'] as $video) {
                $videoEntity = new Video();
                $videoEntity->setTricks($trick);
                $videoEntity->setLink($video['link']);
                $em->persist($videoEntity);
            }

            $em->flush();

            $this->addFlash('success', 'Trick ajouté avec succès');
            return $this->redirectToRoute('index', ['tricksPage' => 'tricks']);
        }

        return $this->render('tricks/add.html.twig', [
            'title' => 'Ajouter un tricks',
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/tricks/supprimer/{slug}", name="tricksDelete")
     */
    public function tricksDelete(TricksRepository $tricksRepository, $slug)
    {

        if (!$this->isGranted('ROLE_USER')) {
            $this->addFlash('error', "Vous n'avez pas accès à cette page");
            return $this->redirectToRoute('index');
        }

        $em = $this->getDoctrine()->getManager();
        $trick = $tricksRepository->findOneBy(['slug' => $slug]);

        $trick->setDeletedAt(new \DateTime());
        $em->persist($trick);
        $em->flush();

        $this->addFlash('success', 'Trick supprimé avec succès.');
        return $this->redirectToRoute('index', ['tricksPage' => 'tricks']);
    }


    /**
     * @Route("/tricks/list", name="tricksList")
     */
    public function tricksList(
        TricksRepository $tricksRepository,
        Request          $request
    )
    {

        $start = $request->request->get('start');
        $number = $request->request->get('number');

        $start = $start - 1;

        $tricks = $tricksRepository->findBetweenStartAndEnd($start, $number);

        return $this->render('tricks/list.html.twig', [
            'title' => 'Tricks',
            'tricks' => $tricks
        ]);
    }
}
