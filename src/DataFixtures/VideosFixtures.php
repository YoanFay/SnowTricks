<?php

namespace App\DataFixtures;

use App\Entity\Images;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class VideosFixtures extends Fixture implements DependentFixtureInterface
{


    /**
     * @param ObjectManager $manager parameter
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {

        $videos
            = [
            [
                'tricks' => $this->getReference('mute'),
                'link'   => 'https://www.youtube.com/embed/jm19nEvmZgM',
            ],
        ];

        foreach ($videos as $videoTab) {
            $video = new Video();
            $video->setTricks($videoTab['tricks']);
            $video->setLink($videoTab['link']);

            $manager->persist($video);
        }

        $manager->flush();

    }


    /**
     * @return string[]
     */
    public function getDependencies(): array
    {

        return [
            TricksFixtures::class,
        ];

    }

}
