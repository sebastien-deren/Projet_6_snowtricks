<?php

namespace App\Service;

use App\Entity\Interface\UserRegisterInterface;
use App\Service\Interface\ConfirmEmailInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MailerService implements ConfirmEmailInterface
{
    public function __construct(
        private readonly MailerInterface       $mailer,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly array                 $botMail)
    {
    }

    /**
     * @param UserRegisterInterface $user
     * @param string $verifiedEmailRouteName
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public
    function sendConfirmation(UserRegisterInterface $user, string $verifiedEmailRouteName): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->botMail['address'], $this->botMail['name']))
            ->to($user->getMail())
            ->subject('Please Confirm your Email')
            ->htmlTemplate('registration/confirmation_email.html.twig');

        $token = hash('md5', $user->getId() . $user->getUsername());
        $completeUrl = $this->urlGenerator->generate($verifiedEmailRouteName,
            ['id' => $user->getId(), 'token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

        $context = $email->getContext();
        $context['signedUrl'] = $completeUrl;
        $email->context($context);

        $this->mailer->send($email);
    }

}