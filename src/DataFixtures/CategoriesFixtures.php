<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            'Grabs',
            'Rotations',
            'Flips',
            'Slides'
        ];

        foreach ($categories as $categoryName) {
            $category = new Categories();
            $category->setName($categoryName);

            $this->addReference($categoryName, $category);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
