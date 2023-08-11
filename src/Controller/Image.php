<?php

namespace App\Controller;

use App\Kernel;
use App\Repository\ImagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Image extends AbstractController
{

    /**
     * @Route("/image/supprimer", name="imageDelete")
     */
    public function imageDelete(
        ImagesRepository $imagesRepository,
        Kernel           $kernel,
        Request          $request
    )
    {

        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $image = $imagesRepository->find($id);

        if ($image === null) {
            return $this->json([
                'result' => false,
            ]);
        }

        $isMain = $image->isMain();
        $trick = $image->getTricks();

        $imagePath = $kernel->getProjectDir().'/public/img/Tricks/'.$image->getTricks()->getSlug().'/'.$image->getName().'.'.$image->getType();

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $em->remove($image);
        $em->flush();

        if ($isMain) {
            $images = $imagesRepository->findBy(['tricks' => $trick]);

            if ($images[0]) {
                $images[0]->setMain(true);

                $em->persist($images[0]);
                $em->flush();
            }
        }

        return $this->json([
            'result' => true,
        ]);
    }


    /**
     * @Route("/image/defaut", name="imageDefault")
     */
    public function imageDefault(
        ImagesRepository $imagesRepository,
        Kernel           $kernel,
        Request          $request
    )
    {

        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $image = $imagesRepository->find($id);

        if ($image === null) {
            return $this->json([
                'result' => false,
            ]);
        }

        $mainImage = $imagesRepository->findOneBy(['tricks' => $image->getTricks(), 'main' => true]);
        $mainImage->setMain(false);
        $image->setMain(true);

        $em->persist($mainImage);
        $em->persist($image);
        $em->flush();

        return $this->json([
            'result' => true,
        ]);
    }
}