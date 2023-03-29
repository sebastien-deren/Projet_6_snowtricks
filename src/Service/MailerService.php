<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MailerService
{
    public function __construct(
        private readonly MailerInterface       $mailer,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly array                 $botMail)
    {
    }

    /**
     * @param User $user
     * @param string $verifiedEmailRouteName
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public
    function sendConfirmation(User $user, string $verifiedEmailRouteName): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->botMail['address'], $this->botMail['name']))
            ->to($user->getMail())
            ->subject('Please Confirm your Email')
            ->htmlTemplate('registration/confirmation_email.html.twig');

        $token = $user->getHash();
        $completeUrl = $this->urlGenerator->generate($verifiedEmailRouteName,
            ['id' => $user->getId(), 'token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

        $context = $email->getContext();
        $context['signedUrl'] = $completeUrl;
        $email->context($context);

        $this->mailer->send($email);
    }

}