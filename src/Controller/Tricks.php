<?php

namespace App\Controller;

use App\Entity\EditTricks;
use App\Entity\Commentaire;
use App\Entity\Images;
use App\Entity\Tricks as TricksEntity;
use App\Entity\Video;
use App\Form\CommentaireType;
use App\Form\Tricks\AddTricksType;
use App\Form\Tricks\EditTricksType;
use App\Repository\EditTricksRepository;
use App\Repository\CommentaireRepository;
use App\Repository\ImagesRepository;
use App\Repository\TricksRepository;
use App\Repository\VideoRepository;
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
        TricksRepository      $tricksRepository,
        CommentaireRepository $commentaireRepository,
        EditTricksRepository  $editTricksRepository,
        ImagesRepository      $imagesRepository,
        Request               $request,
                              $slug
    )
    {

        $trick = $tricksRepository->findOneBy(['slug' => $slug, 'deleted_at' => null]);

        if (!$trick) {
            throw $this->createNotFoundException();
        }

        $lastEdit = $editTricksRepository->findLastEdit($trick);

        if (count($lastEdit) > 0) {
            $lastEdit = $lastEdit[0];
        } else {
            $lastEdit = null;
        }

        $commentaire = new Commentaire();

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $commentaire->setCreatedAt(new \DateTime());
            $commentaire->setTricks($trick);
            $commentaire->setUser($this->getUser());

            $em->persist($commentaire);
            $em->flush();

            return $this->redirectToRoute('tricksDetails', ['slug' => $slug]);

        }

        $tricksCommentaires = $commentaireRepository->findBy(['tricks' => $trick], ['createdAt' => 'DESC']);

        $mainImage = $imagesRepository->findOneBy(['tricks' => $trick, 'main' => true]);

        return $this->render('tricks/details.html.twig', [
            'title' => 'Tricks',
            'trick' => $trick,
            'lastEdit' => $lastEdit,
            'commentaires' => $tricksCommentaires,
            'form' => $form->createView(),
            'mainImage' => $mainImage,
            'slug' => $slug
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

            if (isset($request->files->get('add_tricks')['images'])) {
                $authorizedExt = ['png', 'jpg', 'jpeg', 'webp', 'avif', 'svg'];
                foreach ($request->files->get('add_tricks')['images'] as $key => $image) {
                    $image = $image['images'];
                    if ($image) {
                        if (in_array($image->getClientOriginalExtension(), $authorizedExt) && $image->getSize() <= 500000) {
                            if ($name = $uploadService->uploadTricks($trick->getSlug(), $image)) {
                                $imageEntity = new Images();

                                $imageEntity->setTricks($trick);
                                $imageEntity->setName($name);
                                $imageEntity->setType($image->getClientOriginalExtension());

                                if (isset($request->request->get('add_tricks')['images'][$key])) {
                                    if ($request->request->get('add_tricks')['images'][$key]['main']) {
                                        $imageEntity->setMain(true);
                                    }
                                }

                                $em->persist($imageEntity);
                            }
                        }
                    }
                }
            }
            
            if (isset($request->request->get('add_tricks')['videos'])){
                foreach ($request->request->get('add_tricks')['videos'] as $video) {
                    $videoEntity = new Video();
                    $videoEntity->setTricks($trick);
                    $videoEntity->setLink($video['link']);
                    $em->persist($videoEntity);
                }
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
     * @Route("/tricks/modifier/{slug}", name="tricksEdit")
     */
    public function tricksEdit(
        TricksRepository  $tricksRepository,
        UtilitaireService $utilitaireService,
        VideoRepository   $videoRepository,
        Request           $request,
        UploadService     $uploadService,
        ImagesRepository  $imagesRepository,
                          $slug
    )
    {

        if (!$this->isGranted('ROLE_USER')) {
            $this->addFlash('error', "Vous n'avez pas accès à cette page");
            return $this->redirectToRoute('index');
        }

        $trick = $tricksRepository->findOneBy(['slug' => $slug, 'deleted_at' => null]);

        if (!$trick) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(EditTricksType::class, $trick);

        $editTricks = new EditTricks();

        $editTricks->setOldCategory($trick->getCategory());
        $editTricks->setOldDescription($trick->getDescription());
        $editTricks->setOldName($trick->getName());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $tricksVideos = $videoRepository->findBy(['tricks' => $trick]);

            if (isset($request->request->get('edit_tricks')['videos'])) {
                $videos = $request->request->get('edit_tricks')['videos'];

                foreach ($tricksVideos as $tricksVideo) {
                    foreach ($videos as $key => $video) {
                        if ($video['link'] === $tricksVideo->getLink()) {
                            break;
                        }
                        $em->remove($tricksVideo);
                        unset($video);
                    }
                }

                foreach ($request->request->get('edit_tricks')['videos'] as $video) {

                    $videoEntity = $videoRepository->findOneBy(['tricks' => $trick, 'link' => $video['link']]);

                    if ($videoEntity === null and $video['link'] !== null and $video['link'] !== "") {
                        $videoEntity = new Video();
                        $videoEntity->setTricks($trick);
                        $videoEntity->setLink($video['link']);

                        $em->persist($videoEntity);
                    }
                }
            }

            $count = $imagesRepository->countImage($trick)[0][1];

            if (isset($request->files->get('edit_tricks')['images'])) {
                $authorizedExt = ['png', 'jpg', 'jpeg', 'webp', 'avif', 'svg'];
                foreach ($request->files->get('edit_tricks')['images'] as $key => $image) {
                    $image = $image['images'];
                    if ($image) {
                        if (in_array($image->getClientOriginalExtension(), $authorizedExt) && $image->getSize() <= 500000) {
                            if ($name = $uploadService->uploadTricks($trick->getSlug(), $image)) {
                                $imageEntity = new Images();

                                $imageEntity->setTricks($trick);
                                $imageEntity->setName($name);
                                $imageEntity->setType($image->getClientOriginalExtension());

                                if (isset($request->request->get('edit_tricks')['images'][$key])) {
                                    if ($request->request->get('edit_tricks')['images'][$key]['main']) {

                                        $mainImage = $imagesRepository->findOneBy(['tricks' => $trick, 'main' => true]);

                                        $mainImage->setMain(false);
                                        $em->persist($mainImage);

                                        $imageEntity->setMain(true);
                                    }
                                }

                                if ($count === 0){
                                    $imageEntity->setMain(true);
                                    $count++;
                                }

                                $em->persist($imageEntity);
                            }
                        }
                    }
                }
            }

            $trick->setSlug($utilitaireService->makeSlug($trick->getName()));

            $em->persist($trick);

            $editTricks->setTrick($trick);
            $editTricks->setUpdatedAt(new \DateTime());
            $editTricks->setUpdatedBy($this->getUser());
            $editTricks->setNewCategory($trick->getCategory());
            $editTricks->setNewDescription($trick->getDescription());
            $editTricks->setNewName($trick->getName());

            $em->persist($editTricks);
            $em->flush();

            return $this->redirectToRoute('tricksDetails', ['slug' => $trick->getSlug()]);
        }

        return $this->render('tricks/edit.html.twig', [
            'title' => 'Modifier un tricks',
            'form' => $form->createView(),
            'trick' => $trick
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
        ImagesRepository $imagesRepository,
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
