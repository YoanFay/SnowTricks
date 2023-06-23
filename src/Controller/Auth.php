<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RightsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class Auth extends AbstractController{

    /**
     * @Route("/auth/signIn", name="signIn")
     */
    public function SignIn(AuthenticationUtils $authenticationUtils){


        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('auth/signIn.html.twig', [
            'title' => 'Connexion',
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/auth/signUp", name="signUp")
     */
    public function SignUp(RightsRepository $rightsRepository, Request $request){

        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('login', TextType::class)
            ->add('mail', TextType::class)
            ->add('password', PasswordType::class)
            ->add('submit', SubmitType::class,[
                'label' => "Inscription"
            ])
            ->getForm();
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $right = $rightsRepository->findOneBy(['name' => 'Utilisateur']);

            $user->setCreatedAt(new \DateTime());
            $user->setPassword(password_hash($user->getPassword(), PASSWORD_BCRYPT));
            $user->setRights($right);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('signIn');

        }

        return $this->render('auth/signUp.html.twig', [
            'title' => 'Inscription',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/auth/logout", name="logout")
     */
    public function Logout(){
    }

    /**
     * @Route("/auth/password/ask", name="ask_new_password")
     */
    public function askNewPassword(){

    }
}