<?php

namespace App\DataFixtures;

use App\Entity\Tricks;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TricksFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $users = [
            $this->getReference('Yoan-Fayolle'),
            $this->getReference('Naoy-Elloyaf'),
            $this->getReference('John-Doe'),
        ];

        $tricks = [
            [
                'name' => "Mute",
                'description' => "Saisie de la carre frontside de la planche entre les deux pieds avec la main avant",
                'user' => $users[array_rand($users)],
                'category' => $this->getReference('Grabs'),
                'slug' => 'mute',
            ],
            [
                'name' => "Stalefish",
                'description' => "Saisie de la carre backside de la planche entre les deux pieds avec la main arrière",
                'user' => $users[array_rand($users)],
                'category' => $this->getReference('Grabs'),
                'slug' => 'stalefish',
            ],
            [
                'name' => "Japan air",
                'description' => "Saisie de l'avant de la planche, avec la main avant, du côté de la carre frontside",
                'user' => $users[array_rand($users)],
                'category' => $this->getReference('Grabs'),
                'slug' => 'japanair',
            ],
            [
                'name' => "180",
                'description' => "Un demi-tour",
                'user' => $users[array_rand($users)],
                'category' => $this->getReference('Rotations'),
                'slug' => '180',
            ],
            [
                'name' => "Big foot",
                'description' => "Trois tours",
                'user' => $users[array_rand($users)],
                'category' => $this->getReference('Rotations'),
                'slug' => 'bigfoot',
            ],
            [
                'name' => "Haakon flip",
                'description' => "Manœuvre aérienne réalisée en half-pipe en décollant à reculons, et en effectuant une rotation inversée de 720°.",
                'user' => $users[array_rand($users)],
                'category' => $this->getReference('Rotations'),
                'slug' => 'haakonflip',
            ],
            [
                'name' => "Front flips",
                'description' => "Rotations en avant",
                'user' => $users[array_rand($users)],
                'category' => $this->getReference('Flips'),
                'slug' => 'frontflips',
            ],
            [
                'name' => "Back flips",
                'description' => "Rotations en arrière",
                'user' => $users[array_rand($users)],
                'category' => $this->getReference('Flips'),
                'slug' => 'backflips',
            ],
            [
                'name' => "Nose slide",
                'description' => "Slide avec l'avant de la planche sur la barre",
                'user' => $users[array_rand($users)],
                'category' => $this->getReference('Slides'),
                'slug' => 'noseslide',
            ],
            [
                'name' => "Tail slide",
                'description' => "Slide avec l'arrière de la planche sur la barre",
                'user' => $users[array_rand($users)],
                'category' => $this->getReference('Slides'),
                'slug' => 'tailslide',
            ],
        ];

        foreach ($tricks as $trickTab) {
            $trick = new Tricks();
            $trick->setName($trickTab['name']);
            $trick->setDescription($trickTab['description']);
            $trick->setSlug($trickTab['slug']);
            $trick->setCreatedAt(new \DateTime());
            $trick->setUser($trickTab['user']);
            $trick->setCategory($trickTab['category']);

            $this->addReference($trickTab['slug'], $trick);

            $manager->persist($trick);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UsersFixtures::class,
            CategoriesFixtures::class,
        );
    }
}
