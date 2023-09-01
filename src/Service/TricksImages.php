<?php

namespace App\Service;

use App\Entity\Images;
use App\Kernel;
use App\Repository\ImagesRepository;

class  TricksImages
{

    private $imagesRepository;
    private $uploadService;


    public function __construct(ImagesRepository $imagesRepository, UploadService $uploadService)
    {
        $this->imagesRepository = $imagesRepository;
        $this->uploadService = $uploadService;
    }


    public function addTricks($images, $trick, $em)
    {

        $authorizedExt = ['png', 'jpg', 'jpeg', 'webp', 'avif', 'svg'];
        foreach ($images as $key => $image) {
            $image = $image['images'];
            if ($image) {
                if (in_array($image->getClientOriginalExtension(), $authorizedExt) && $image->getSize() <= 500000) {
                    if ($name = $this->uploadService->uploadTricks($trick->getSlug(), $image)) {
                        $imageEntity = new Images();

                        $imageEntity->setTricks($trick);
                        $imageEntity->setName($name);
                        $imageEntity->setType($image->getClientOriginalExtension());

                        if (isset($images[$key])) {
                            if ($images[$key]['main']) {
                                $imageEntity->setMain(true);
                            }
                        }

                        $em->persist($imageEntity);
                    }
                }
            }
        }
    }

    public function editTricks($images, $trick, $em){

        $count = $this->imagesRepository->countImage($trick)[0][1];
        $authorizedExt = ['png', 'jpg', 'jpeg', 'webp', 'avif', 'svg'];

        foreach ($images as $key => $image) {
            $image = $image['images'];
            if ($image) {
                if (in_array($image->getClientOriginalExtension(), $authorizedExt) && $image->getSize() <= 500000) {
                    if ($name = $this->uploadService->uploadTricks($trick->getSlug(), $image)) {
                        $imageEntity = new Images();

                        $imageEntity->setTricks($trick);
                        $imageEntity->setName($name);
                        $imageEntity->setType($image->getClientOriginalExtension());

                        if (isset($request->request->get('edit_tricks')['images'][$key])) {
                            if ($images[$key]['main']) {

                                $mainImage = $this->imagesRepository->findOneBy(['tricks' => $trick, 'main' => true]);

                                $mainImage->setMain(false);
                                $em->persist($mainImage);

                                $imageEntity->setMain(true);
                            }
                        }

                        if ($count === 0) {
                            $imageEntity->setMain(true);
                            $count++;
                        }

                        $em->persist($imageEntity);
                    }
                }
            }
        }
    }

}