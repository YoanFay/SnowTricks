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
                'role' => 'ROLE_USER'
            ],
            [
                'name' => 'Administrateur',
                'role' => 'ROLE_ADMIN'
            ],
            [
                'name' => 'Super-Administrateur',
                'role' => 'ROLE_SUPER_ADMIN'
            ],
        ];

        foreach ($rights as $rightTab) {
            $right = new Rights();
            $right->setName($rightTab['name']);
            $right->setRole($rightTab['role']);

            $this->addReference($rightTab['name'], $right);

            $manager->persist($right);
        }

        $manager->flush();
    }
}
