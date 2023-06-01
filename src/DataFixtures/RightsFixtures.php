<?php

namespace App\DataFixtures;

use App\Entity\Rights;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RightsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $rights = [
            [
                'name' => 'Utilisateur',
                'level' => 10
            ],
            [
                'name' => 'Administrateur',
                'level' => 70
            ],
            [
                'name' => 'Super-Administrateur',
                'level' => 99
            ],
        ];

        foreach ($rights as $rightTab) {
            $right = new Rights();
            $right->setName($rightTab['name']);
            $right->setLevel($rightTab['level']);

            $this->addReference($rightTab['name'], $right);

            $manager->persist($right);
        }

        $manager->flush();
    }
}
