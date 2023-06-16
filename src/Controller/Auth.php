<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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

        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('login', TextType::class)
            ->add('mail', TextType::class)
            ->add('password', PasswordType::class)
            ->getForm();
        ;

        return $this->render('auth/signUp.html.twig', [
            'title' => 'Inscription',
            'form' => $form->createView()
        ]);
    }
}