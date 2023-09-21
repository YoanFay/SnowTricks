<?php

namespace App\DataFixtures;

use App\Entity\Images;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ImagesFixtures extends Fixture implements DependentFixtureInterface
{


    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {

        $images
            =[
                [
                    'tricks' => $this->getReference('mute'),
                    'name' => '01',
                    'type' => 'jpg',
                    'main' => true
                ],
                [
                    'tricks' => $this->getReference('mute'),
                    'name' => '02',
                    'type' => 'jpeg',
                    'main' => false
                ],
                [
                    'tricks' => $this->getReference('mute'),
                    'name' => '03',
                    'type' => 'jpg',
                    'main' => false
                ],
                [
                    'tricks' => $this->getReference('stalefish'),
                    'name' => '01',
                    'type' => 'jpg',
                    'main' => true
                ],
                [
                    'tricks' => $this->getReference('japanair'),
                    'name' => '01',
                    'type' => 'jpg',
                    'main' => true
                ],
                [
                    'tricks' => $this->getReference('180'),
                    'name' => '01',
                    'type' => 'jpg',
                    'main' => true
                ],
                [
                    'tricks' => $this->getReference('bigfoot'),
                    'name' => '01',
                    'type' => 'jpg',
                    'main' => true
                ],
                [
                    'tricks' => $this->getReference('haakonflip'),
                    'name' => '01',
                    'type' => 'jpg',
                    'main' => true
                ],
                [
                    'tricks' => $this->getReference('frontflips'),
                    'name' => '01',
                    'type' => 'jpg',
                    'main' => true
                ],
                [
                    'tricks' => $this->getReference('backflips'),
                    'name' => '01',
                    'type' => 'jpg',
                    'main' => true
                ],
                [
                    'tricks' => $this->getReference('noseslide'),
                    'name' => '01',
                    'type' => 'jpg',
                    'main' => true
                ],
                [
                    'tricks' => $this->getReference('tailslide'),
                    'name' => '01',
                    'type' => 'jpg',
                    'main' => true
                ],
            ];

        foreach ($images as $imageTab) {
            $image = new Images();
            $image->setName($imageTab['name']);
            $image->setTricks($imageTab['tricks']);
            $image->setType($imageTab['type']);

            if ($imageTab['main']){
                $image->setMain(true);
            }

            $manager->persist($image);
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