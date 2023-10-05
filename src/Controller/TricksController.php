<?php

namespace App\Controller;

use App\Entity\EditTricks;
use App\Entity\Commentaire;
use App\Entity\Tricks as TricksEntity;
use App\Form\CommentaireType;
use App\Form\Tricks\AddTricksType;
use App\Form\Tricks\EditTricksType;
use App\Kernel;
use App\Repository\EditTricksRepository;
use App\Repository\CommentaireRepository;
use App\Repository\ImagesRepository;
use App\Repository\TricksRepository;
use App\Service\TricksImagesService;
use App\Service\TricksVideosService;
use App\Service\UtilitaireService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TricksController extends AbstractController
{


    /**
     * @param TricksRepository      $tricksRepository     parameter
     * @param CommentaireRepository $commentaireRepo      parameter
     * @param EditTricksRepository  $editTricksRepository parameter
     * @param ImagesRepository      $imagesRepository     parameter
     * @param Request               $request              parameter
     * @param string                $slug                 parameter
     *
     * @return RedirectResponse|Response
     *
     * @Route("/tricks/details/{slug}", name="tricksDetails")
     */
    public function tricksDetails(TricksRepository $tricksRepository, CommentaireRepository $commentaireRepo, EditTricksRepository $editTricksRepository, ImagesRepository $imagesRepository, Request $request, string $slug)
    {

        $trick = $tricksRepository->findOneBy(
            [
                'slug'       => $slug,
                'deleted_at' => null
            ]
        );

        if (!$trick) {
            throw $this->createNotFoundException();
        }

        //Cherche la dernière modification du trick
        $lastEdit = $editTricksRepository->findLastEdit($trick);

        $comment = new Commentaire();

        $form = $this->createForm(CommentaireType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();

            $comment->setCreatedAt(new \DateTime());
            $comment->setTricks($trick);
            try {
                $comment->setUser($this->getUser());
            }catch (\Exception $e){
                $this->addFlash('danger', "Votre commentaire n'a pas pu être publié");
            }

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success', 'Votre commentaire a été posté avec succès');
            return $this->redirectToRoute('tricksDetails', ['slug' => $slug]);
        }

        $tricksComments = $commentaireRepo->findBy(['tricks' => $trick], ['createdAt' => 'DESC']);

        $mainImage = $imagesRepository->findOneBy(
            [
                'tricks' => $trick,
                'main'   => true
            ]
        );

        return $this->render(
            'tricks/details.html.twig',
            [
                'trick'        => $trick,
                'lastEdit'     => $lastEdit,
                'commentaires' => $tricksComments,
                'form'         => $form->createView(),
                'mainImage'    => $mainImage,
                'slug'         => $slug
            ]
        );

    }


    /**
     * @param Request             $request           parameter
     * @param UtilitaireService   $utilitaireService parameter
     * @param TricksImagesService $tricksImages      parameter
     * @param TricksVideosService $tricksVideos      parameter
     *
     * @return RedirectResponse|Response
     *
     * @Route("/tricks/ajouter", name="tricksAdd")
     */
    public function tricksAdd(Request $request, UtilitaireService $utilitaireService, TricksImagesService $tricksImages, TricksVideosService $tricksVideos)
    {

        if (!$this->isGranted('ROLE_USER')) {
            $this->addFlash('error', "Vous n'avez pas accès à cette page");
            return $this->redirectToRoute('index');
        }

        $trick = new TricksEntity();

        $form = $this->createForm(AddTricksType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();

            //Génère le slug du trick
            $trick->setSlug($utilitaireService->makeSlug($trick->getName()));
            $trick->setUser($this->getUser());

            $manager->persist($trick);

            if (isset($request->files->get('add_tricks')['images'])) {
                //Ajoute les images liées au trick
                $tricksImages->addTricks($request->files->get('add_tricks')['images'], $trick, $manager, $request);
            }

            if (isset($request->request->get('add_tricks')['videos'])) {
                //Ajoute les vidéos liées au trick
                $tricksVideos->addTricks($trick, $manager);
            }

            $manager->flush();

            $this->addFlash('success', 'Trick ajouté avec succès');
            return $this->redirectToRoute('index', ['tricksPage' => 'tricks']);
        }//end if

        return $this->render(
            'tricks/add.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }


    /**
     * @Route("/tricks/modifier/{slug}", name="tricksEdit")
     */
    public function tricksEdit(TricksRepository $tricksRepository, UtilitaireService $utilitaireService, Request $request, TricksImagesService $tricksImages, TricksVideosService $tricksVideos, Kernel $kernel, string $slug)
    {

        if (!$this->isGranted('ROLE_USER')) {
            $this->addFlash('error', "Vous n'avez pas accès à cette page");
            return $this->redirectToRoute('index');
        }

        $trick = $tricksRepository->findOneBy(['slug'       => $slug,
                                               'deleted_at' => null]);
        $oldSlug = $trick->getSlug();

        if (!$trick) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(EditTricksType::class, $trick);

        $editTricks = new EditTricks($trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();

            $trick->setSlug($utilitaireService->makeSlug($trick->getName()));

            $projectDir = $kernel->getProjectDir().'/public/img/Tricks/';

            rename($projectDir.$oldSlug,$projectDir.$trick->getSlug());

            if (isset($request->request->get('edit_tricks')['videos'])) {
                $tricksVideos->editTricks($trick, $manager, $request);
            }

            if (isset($request->files->get('edit_tricks')['images'])) {
                $tricksImages->editTricks($request->files->get('edit_tricks')['images'], $trick, $manager, $request);
            }

            $manager->persist($trick);

            $editTricks->newValue($trick, $this->getUser());

            $manager->persist($editTricks);

            $manager->flush();

            return $this->redirectToRoute('tricksDetails', ['slug' => $trick->getSlug()]);
        }

        return $this->render('tricks/edit.html.twig', [
            'form'  => $form->createView(),
            'trick' => $trick
        ]);
    }


    /**
     * @param TricksRepository $tricksRepository parameter
     * @param string           $slug             parameter
     *
     * @return RedirectResponse
     *
     * @Route("/tricks/supprimer/{slug}", name="tricksDelete")
     */
    public function tricksDelete(TricksRepository $tricksRepository, string $slug): RedirectResponse
    {

        if (!$this->isGranted('ROLE_USER')) {
            $this->addFlash('error', "Vous n'avez pas accès à cette page");
            return $this->redirectToRoute('index');
        }

        $manager = $this->getDoctrine()->getManager();
        $trick = $tricksRepository->findOneBy(['slug' => $slug]);

        $trick->setDeletedAt(new \DateTime());
        $manager->persist($trick);
        $manager->flush();

        $this->addFlash('success', 'Trick supprimé avec succès.');
        return $this->redirectToRoute('index', ['tricksPage' => 'tricks']);
    }


    /**
     * @param TricksRepository $tricksRepository parameter
     * @param Request          $request          parameter
     *
     * @return Response
     *
     * @Route("/tricks/list", name="tricksList")
     */
    public function tricksList(TricksRepository $tricksRepository, Request $request): Response
    {

        $start = $request->request->get('start');
        $number = $request->request->get('number');

        $start = ($start - 1);

        $tricks = $tricksRepository->findBetweenStartAndEnd($start, $number);

        return $this->render(
            'tricks/list.html.twig',
            [
                'tricks' => $tricks
            ]
        );
    }


}
