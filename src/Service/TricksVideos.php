<?php

namespace App\Service;

use App\Entity\Images;
use App\Entity\Tricks;
use App\Entity\Video;
use App\Kernel;
use App\Repository\ImagesRepository;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class  TricksVideos
{

    /**
     * @var VideoRepository
     */
    private $videoRepository;

    /**
     * @var UploadService
     */
    private $uploadService;

    /**
     * @var Request
     */
    private $request;


    /**
     * @param VideoRepository $videoRepository parameter
     * @param UploadService   $uploadService   parameter
     */
    public function __construct(VideoRepository $videoRepository, UploadService $uploadService, Request $request)
    {

        $this->videoRepository = $videoRepository;
        $this->uploadService = $uploadService;
        $this->request = $request;

    }//end __construct()


        /**
         * @param Tricks                 $trick   parameter
         * @param EntityManagerInterface $manager parameter
         *
         * @return void
         */
    public function addTricks(Tricks $trick, EntityManagerInterface $manager)
    {
        foreach ($this->request->request->get('add_tricks')['videos'] as $video) {
            $videoEntity = new Video();
            $videoEntity->setTricks($trick);
            $videoEntity->setLink($video['link']);
            $manager->persist($videoEntity);
        }
    }


    /**
     * @param Tricks                 $trick   parameter
     * @param EntityManagerInterface $manager parameter
     *
     * @return void
     */
    public function editTricks(Tricks $trick, EntityManagerInterface $manager)
    {

        $tricksVideos = $this->videoRepository->findBy(['tricks' => $trick]);

        $videos = $this->request->request->get('edit_tricks')['videos'];

        foreach ($tricksVideos as $tricksVideo) {
            foreach ($videos as $video) {
                if ($video['link'] === $tricksVideo->getLink()) {
                    break;
                }
                $manager->remove($tricksVideo);
                unset($video);
            }
        }

        foreach ($this->request->request->get('edit_tricks')['videos'] as $video) {

            $videoEntity = $this->videoRepository->findOneBy(
                ['tricks' => $trick,
                 'link'   => $video['link'],
                ]
            );

            if ($videoEntity === null and $video['link'] !== null and $video['link'] !== "") {
                $videoEntity = new Video();
                $videoEntity->setTricks($trick);
                $videoEntity->setLink($video['link']);

                $manager->persist($videoEntity);
            }
        }

    }


}