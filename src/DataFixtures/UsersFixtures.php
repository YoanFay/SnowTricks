<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UsersFixtures extends Fixture implements DependentFixtureInterface
{


    /**
     * @param ObjectManager $manager parameter
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {

        $users
            = [
            [
                'login' => 'Yoan-Fayolle',
                'mail' => 'yoanfayolle.yf@gmail.com',
                'password' => 'MotDePasseSA',
                'right' => $this->getReference('Super-Administrateur')
            ],
            [
                'login' => 'Naoy-Elloyaf',
                'mail' => 'yoanfayolle.yf@gmail.com',
                'password' => 'MotDePasseA',
                'right' => $this->getReference('Administrateur')
            ],
            [
                'login' => 'John-Doe',
                'mail' => 'yoanfayolle.yf@gmail.com',
                'password' => 'MotDePasse',
                'right' => $this->getReference('Utilisateur')
            ],
        ];

        foreach ($users as $userTab) {
            $user = new User();
            $user->setLogin($userTab['login']);
            $user->setMail($userTab['mail']);
            $user->setPassword(password_hash($userTab['password'], PASSWORD_BCRYPT));
            $user->setRights($userTab['right']);
            $user->setCreatedAt(new \DateTime());
            $user->setConfirmed(true);

            $this->addReference($userTab['login'], $user);

            $manager->persist($user);
        }

        $manager->flush();

    }//end load()


    /**
     * @return string[]
     */
    public function getDependencies(): array
    {

        return [
            RightsFixtures::class,
        ];

    }//end getDependencies()


}//end UsersFixtures
