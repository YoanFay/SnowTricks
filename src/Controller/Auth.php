<?php

namespace App\Controller;

use App\Entity\PasswordRequest;
use App\Entity\User;
use App\Repository\PasswordRequestRepository;
use App\Repository\RightsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
    public function SignUp(RightsRepository $rightsRepository, Request $request, UrlGeneratorInterface $urlGenerator, MailerInterface $mailer)
    {

        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('login', TextType::class, [
                'label' => 'Identifiant'
            ])
            ->add('mail', TextType::class, [
                'label' => 'Email'
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe'
            ])
            ->add('passwordConfirm', PasswordType::class, [
                'mapped' => false,
                'label' => 'Confirmer mot de passe'
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Inscription"
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $right = $rightsRepository->findOneBy(['name' => 'Utilisateur']);

            $user->setCreatedAt(new \DateTime());
            $user->setPassword(password_hash($user->getPassword(), PASSWORD_BCRYPT));
            $user->setRights($right);

            $em->persist($user);
            $em->flush();

            $url = $urlGenerator->generate('confirm_user', ['idUser' => $user->getId()], 0);

            $email = (new Email())
                ->from($user->getMail())
                ->to('yoanfayolle.yf@gmail.com')
                ->subject("SnowTricks - Validation d'inscription")
                ->html("<div style='background-color: #f5f5f5; padding: 20px;'><h1>Validation d'inscription</h1><p>Merci de vous être inscrit ! Pour activer votre compte, veuillez cliquer sur le bouton ci-dessous :</p><a href=".$url." style='display: inline-block; background-color: #008CBA; color: #fff; padding: 10px 20px; text-decoration: none;'>Valider mon compte</a><p>Si le bouton ne fonctionne pas, vous pouvez également copier/coller le lien suivant dans la barre d'adresse de votre navigateur :</p><p>".$url."</p><p>Merci,</p><p>L'équipe de votre site</p>");

            $mailer->send($email);

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
    public function askNewPassword(Request $request, UserRepository $userRepository, MailerInterface $mailer, UrlGeneratorInterface $urlGenerator)
    {

        $form = $this->createFormBuilder()
            ->add('login', TextType::class, [
                'label' => 'Identifiant'
            ])
            ->add('mail', TextType::class, [
                'label' => 'Email'
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Envoyer"
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $userRepository->findOneBy(['login' => $data['login'], 'mail' => $data['mail']]);

            if ($user) {

                $passwordRequest = new PasswordRequest();
                $passwordRequest->setUser($user);
                $passwordRequest->setToken(md5(uniqid()));
                $passwordRequest->setCompleted(false);

                $em = $this->getDoctrine()->getManager();
                $em->persist($passwordRequest);
                $em->flush();

                $url = $urlGenerator->generate('reset_password', ['token' => $passwordRequest->getToken()], 0);

                $email = (new Email())
                    ->from($user->getMail())
                    ->to('yoanfayolle.yf@gmail.com')
                    ->subject('SnowTricks - Demande de mot de passe')
                    ->html("<h1>Réinitialisation de mot de passe</h1><p>Cher utilisateur,</p><p>Vous avez demandé une réinitialisation de votre mot de passe. Veuillez cliquer sur le bouton ci-dessous pour procéder à la réinitialisation :</p>
  <a href=".$url." style='display: inline-block; background-color: #008CBA; color: #fff; padding: 10px 20px; text-decoration: none;'>Réinitialiser le mot de passe</a><p>Si vous n'avez pas demandé de réinitialisation de mot de passe, veuillez ignorer cet e-mail.</p><p>Cordialement,</p><p>L'équipe de support</p>");

                $mailer->send($email);
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

        $form = $this->createFormBuilder()
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe'
            ])
            ->add('passwordConfirm', PasswordType::class, [
                'mapped' => false,
                'label' => 'Confirmer mot de passe'
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Envoyer"
            ])
            ->getForm();

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

        if (!$user || $user->isConfirmed()){
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