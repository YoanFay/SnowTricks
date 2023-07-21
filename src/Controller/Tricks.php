<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Tricks as TricksEntity;
use App\Entity\Video;
use App\Form\Tricks\AddTricksType;
use App\Repository\TricksRepository;
use App\Service\UploadService;
use App\Service\UtilitaireService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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


    /**
     * @Route("/tricks/ajouter", name="tricksAdd")
     */
    public function tricksAdd(TricksRepository $tricksRepository, Request $request, UtilitaireService $utilitaireService, UploadService $uploadService)
    {

        if(!$this->isGranted('ROLE_USER')){
            $this->addFlash('error', "Vous n'avez pas accès à cette page");
            return $this->redirectToRoute('index');
        }

        $trick = new TricksEntity();

        $form = $this->createForm(AddTricksType::class, $trick);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();

            $trick->setSlug($utilitaireService->makeSlug($trick->getName()));
            $trick->setUser($this->getUser());

            $em->persist($trick);

            $images = $request->files->get('add_tricks')['images'];
            $authorizedExt = ['png', 'jpg', 'jpeg', 'webp', 'avif', 'svg'];

            foreach ($images as $image){
                if (in_array($image->getClientOriginalExtension(), $authorizedExt) && $image->getSize() <= 500000){
                    if($name = $uploadService->uploadTricks($trick->getSlug(), $image)){
                        $imageEntity = new Images();

                        $imageEntity->setTricks($trick);
                        $imageEntity->setName($name);
                        $imageEntity->setType($image->getClientOriginalExtension());
                        $em->persist($imageEntity);
                    }
                }
            }

            foreach ($request->request->get('add_tricks')['videos'] as $video){
                $videoEntity = new Video();
                $videoEntity->setTricks($trick);
                $videoEntity->setLink($video['link']);
                $em->persist($videoEntity);
            }

            $em->flush();

            $this->addFlash('success', 'Trick ajouté avec succès');
            return $this->redirectToRoute('index', ['tricksPage'=> 'tricks']);
        }

        return $this->render('tricks/add.html.twig', [
            'title' => 'Ajouter un tricks',
            'form' => $form->createView()
        ]);
    }
}