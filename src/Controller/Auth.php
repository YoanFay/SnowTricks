<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class Auth extends AbstractController{

    /**
     * @Route("/auth/signIn", name="signIn")
     */
    public function SignIn(){

        return $this->render('auth/signIn.html.twig', [
            'title' => 'Connexion'
        ]);
    }

    /**
     * @Route("/auth/signUp", name="signUp")
     */
    public function SignUp(){

        return $this->render('auth/signUp.html.twig', [
            'title' => 'Inscription'
        ]);
    }
}