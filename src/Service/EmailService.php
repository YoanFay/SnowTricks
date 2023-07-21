<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EmailService
{

    const EMAIL_TO = 'yoanfayolle.yf@gmail.com';

    private $urlGenerator;

    private $mailer;


    public function __construct(UrlGeneratorInterface $urlGenerator, MailerInterface $mailer)
    {

        $this->mailer = $mailer;
        $this->urlGenerator = $urlGenerator;
    }


    public function EMailInscription(User $user)
    {

        $url = $this->urlGenerator->generate('confirm_user', ['idUser' => $user->getId()], 0);

        $from = $user->getMail();
        $subject = "Validation d'inscription";
        $message = "Merci de vous être inscrit ! Pour activer votre compte, veuillez cliquer sur le bouton ci-dessous :</p><a href=".$url." style='display: inline-block; background-color: #008CBA; color: #fff; padding: 10px 20px; text-decoration: none;'>Valider mon compte</a><p>Si le bouton ne fonctionne pas, vous pouvez également copier/coller le lien suivant dans la barre d'adresse de votre navigateur :</p><p>".$url;

        $this->sendEmail($from, $subject, $message);

    }


    private function sendEmail($from, $subject, $message)
    {

        $email = (new Email())
            ->from($from)
            ->to(self::EMAIL_TO)
            ->subject("SnowTricks - ".$subject)
            ->html("<div style='background-color: #f5f5f5; padding: 20px;'><h1>".$subject."</h1><p>".$message."</p><p>Merci,</p><p>L'équipe Snowtricks</p>");

        $this->mailer->send($email);

    }


    public function EMailForgotPassword(User $user, $token)
    {

        $url = $this->urlGenerator->generate('reset_password', ['token' => $token], 0);

        $from = $user->getMail();
        $subject = "Demande de mot de passe";
        $message = "Vous avez demandé une réinitialisation de votre mot de passe. Veuillez cliquer sur le bouton ci-dessous pour procéder à la réinitialisation :</p><a href=".$url." style='display: inline-block; background-color: #008CBA; color: #fff; padding: 10px 20px; text-decoration: none;'>Réinitialiser le mot de passe</a><p>Si vous n'avez pas demandé de réinitialisation de mot de passe, veuillez ignorer cet e-mail.";

        $this->sendEmail($from, $subject, $message);

    }

}