<?php

namespace App\Service;

use App\Entity\Images;
use App\Entity\Tricks;
use App\Repository\ImagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class TricksImages
{

    /**
     * @var ImagesRepository
     */
    private $imagesRepository;

    /**
     * @var UploadService
     */
    private $uploadService;


    /**
     * @param ImagesRepository $imagesRepository parameter
     * @param UploadService    $uploadService    parameter
     */
    public function __construct(ImagesRepository $imagesRepository, UploadService $uploadService)
    {

        $this->imagesRepository = $imagesRepository;
        $this->uploadService = $uploadService;

    }//end __construct()


    /**
     * @param array                  $images  parameter
     * @param Tricks                 $trick   parameter
     * @param EntityManagerInterface $manager parameter
     *
     * @return void
     */
    public function addTricks(array $images, Tricks $trick, EntityManagerInterface $manager, $request)
    {

        $authorizedExt = [
            'png',
            'jpg',
            'jpeg',
            'webp',
            'avif',
            'svg'
        ];
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
                            if (isset($request->request->get('add_tricks')['images']) && isset($request->request->get('add_tricks')['images'][$key]['main'])) {
                                $imageEntity->setMain(true);
                            }
                        }

                        $manager->persist($imageEntity);
                    }
                }
            }
        }//end foreach

    }


    /**
     * @param array                  $images  parameter
     * @param Tricks                 $trick   parameter
     * @param EntityManagerInterface $manager parameter
     *
     * @return void
     */
    public function editTricks(array $images, Tricks $trick, EntityManagerInterface $manager, Request $request)
    {

        $count = $this->imagesRepository->countImage($trick)[0][1];
        $authorizedExt = [
            'png',
            'jpg',
            'jpeg',
            'webp',
            'avif',
            'svg'
        ];

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
                            if ($request->request->get('edit_tricks')['images'][$key]['main']) {

                                $mainImage = $this->imagesRepository->findOneBy(
                                    [
                                        'tricks' => $trick,
                                        'main'   => true
                                    ]
                                );

                                if ($mainImage){
                                    $mainImage->setMain(false);
                                    $manager->persist($mainImage);
                                }

                                $imageEntity->setMain(true);
                            }
                        }

                        if ($count === 0) {
                            $imageEntity->setMain(true);
                            $count++;
                        }

                        $manager->persist($imageEntity);
                    }
                }
            }
        }//end foreach

    }


}