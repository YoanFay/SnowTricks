<?php

namespace App\Controller;

use App\Kernel;
use App\Repository\ImagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Image extends AbstractController
{


    /**
     * @param ImagesRepository $imagesRepository parameter
     * @param Kernel           $kernel           parameter
     * @param Request          $request          parameter
     *
     * @return JsonResponse
     *
     * @Route("/image/supprimer", name="imageDelete")
     */
    public function imageDelete(ImagesRepository $imagesRepository, Kernel $kernel, Request $request): JsonResponse
    {

        $manager = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $image = $imagesRepository->find($id);

        if ($image === null) {
            return $this->json(
                [
                    'result' => false,
                ]
            );
        }

        $isMain = $image->isMain();
        $trick = $image->getTricks();

        $imagePath = $kernel->getProjectDir().'/public/img/Tricks/'.$image->getTricks()->getSlug().'/'.$image->getName().'.'.$image->getType();

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $manager->remove($image);
        $manager->flush();

        if ($isMain) {
            $images = $imagesRepository->findBy(['tricks' => $trick]);

            if ($images[0]) {
                $images[0]->setMain(true);

                $manager->persist($images[0]);
                $manager->flush();
            }
        }

        return $this->json(
            [
                'result' => true,
            ]
        );

    }


    /**
     * @param ImagesRepository $imagesRepository parameter
     * @param Request          $request          parameter
     *
     * @return JsonResponse
     *
     * @Route("/image/defaut", name="imageDefault")
     */
    public function imageDefault(ImagesRepository $imagesRepository, Request $request): JsonResponse
    {

        $manager = $this->getDoctrine()->getManager();
        $idImage = $request->request->get('id');
        $image = $imagesRepository->find($idImage);

        if ($image === null) {
            return $this->json(
                [
                    'result' => false,
                ]
            );
        }

        $mainImage = $imagesRepository->findOneBy(
            [
                'tricks' => $image->getTricks(),
                'main'   => true
            ]
        );
        $mainImage->setMain(false);
        $image->setMain(true);

        $manager->persist($mainImage);
        $manager->persist($image);
        $manager->flush();

        return $this->json([
            'result' => true,
        ]);

    }


}
