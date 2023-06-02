<?php

namespace App\DataFixtures;

use App\Entity\Images;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ImagesFixtures extends Fixture implements DependentFixtureInterface {

    public function load(ObjectManager $manager): void
    {

        $images = [
            [
                'tricks' => $this->getReference('mute'),
                'name' => '01',
                'type' => 'jpg'
            ],
            [
                'tricks' => $this->getReference('stalefish'),
                'name' => '01',
                'type' => 'jpg'
            ],
            [
                'tricks' => $this->getReference('japanair'),
                'name' => '01',
                'type' => 'jpg'
            ],
            [
                'tricks' => $this->getReference('180'),
                'name' => '01',
                'type' => 'jpg'
            ],
            [
                'tricks' => $this->getReference('bigfoot'),
                'name' => '01',
                'type' => 'jpg'
            ],
            [
                'tricks' => $this->getReference('haakonflip'),
                'name' => '01',
                'type' => 'jpg'
            ],
            [
                'tricks' => $this->getReference('frontflips'),
                'name' => '01',
                'type' => 'jpg'
            ],
            [
                'tricks' => $this->getReference('backflips'),
                'name' => '01',
                'type' => 'jpg'
            ],
            [
                'tricks' => $this->getReference('noseslide'),
                'name' => '01',
                'type' => 'jpg'
            ],
            [
                'tricks' => $this->getReference('tailslide'),
                'name' => '01',
                'type' => 'jpg'
            ],
        ];

        foreach ($images as $imageTab){
            $image = new Images();
            $image->setName($imageTab['name']);
            $image->setTricks($imageTab['tricks']);
            $image->setType($imageTab['type']);

            $manager->persist($image);
        }

        $manager->flush();

    }

    public function getDependencies()
    {
        return array(
            TricksFixtures::class,
        );
    }
}