<?php

namespace App\Controller;

use App\Entity\PasswordRequest;
use App\Entity\User;
use App\Form\Auth\AskNewPasswordType;
use App\Form\Auth\ResetPasswordType;
use App\Form\Auth\SignUpType;
use App\Repository\PasswordRequestRepository;
use App\Repository\RightsRepository;
use App\Repository\UserRepository;
use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;

class Auth extends AbstractController
{

    /**
     * @Route("/auth/connexion", name="signIn")
     */
    public function SignIn(AuthenticationUtils $authenticationUtils)
    {

        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('auth/signIn.html.twig', [
            'title' => 'Connexion',
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }


    /**
     * @Route("/auth/inscription", name="signUp")
     */
    public function SignUp(RightsRepository $rightsRepository, Request $request, EmailService $emailService)
    {

        $user = new User();

        $form = $this->createForm(SignUpType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $right = $rightsRepository->findOneBy(['name' => 'Utilisateur']);

            $user->setCreatedAt(new \DateTime());
            $user->setPassword(password_hash($user->getPassword(), PASSWORD_BCRYPT));
            $user->setRights($right);

            $em->persist($user);
            $em->flush();

            $emailService->EMailInscription($user);

            $this->addFlash('success', 'Un email de confirmation vous a été envoyé');
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
    public function Logout()
    {
    }


    /**
     * @Route("/auth/mot-de-passe-oublie", name="ask_new_password")
     */
    public function askNewPassword(Request $request, UserRepository $userRepository, EmailService $emailService)
    {

        $form = $this->createForm(AskNewPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $userRepository->findOneBy(['login' => $data['login']]);

            if ($user) {

                $passwordRequest = new PasswordRequest();
                $passwordRequest->setUser($user);
                $passwordRequest->setToken(md5(uniqid()));
                $passwordRequest->setCompleted(false);

                $em = $this->getDoctrine()->getManager();
                $em->persist($passwordRequest);
                $em->flush();

                $emailService->EMailForgotPassword($user, $passwordRequest->getToken());

                $this->addFlash('success', 'Demande de nouveau mot de passe envoyé');
                return $this->redirectToRoute('index');
            }
        }

        return $this->render('auth/askNewPassword.html.twig', [
            'title' => 'Mot de passe oublié',
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/auth/changement-mot-de-passe/{token}", name="reset_password")
     */
    public function resetPassword(Request $request, PasswordRequestRepository $passwordRequestRepository, $token)
    {

        $passwordRequest = $passwordRequestRepository->findOneBy(['token' => $token]);

        if ($passwordRequest->isCompleted()) {
            $this->addFlash('danger', 'Le mot de passe a déjà été modifié');
            return $this->redirectToRoute('signIn');
        }

        $form = $this->createForm(ResetPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $user = $passwordRequest->getUser();

            $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
            $passwordRequest->setCompleted(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->persist($passwordRequest);
            $em->flush();

            $this->addFlash('success', 'Mot de passe modifié avec succès');

            return $this->redirectToRoute('signIn');
        }

        return $this->render('auth/resetPassword.html.twig', [
            'title' => 'Nouveau mot de passe',
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/auth/inscription/confirmation/{idUser}", name="confirm_user")
     */
    public function confirmUser(UserRepository $userRepository, $idUser)
    {

        $user = $userRepository->find($idUser);

        if (!$user || $user->isConfirmed()) {
            return $this->redirectToRoute('signIn');
        }

        $user->setConfirmed(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'Compte validé avec succès');

        return $this->redirectToRoute('signIn');
    }
}