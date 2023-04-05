<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MailerService
{
    private TemplatedEmail $email;

    public function __construct(
        private readonly MailerInterface       $mailer,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly array                 $botMail = ['address' => 'default@example.com', 'name' => 'Mail bot'],
    )
    {
        $this->email = (new TemplatedEmail())->from(new Address($this->botMail['address'], $this->botMail['name']));
    }

    /**
     * @param Address $address
     * @param array $context
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public
    function sendResetPasswordMail(Address $address, array $context): void
    {
        $this->setMail($address, $context);
        $this->email
            ->subject('reset your password')
            ->htmlTemplate('reset_password/reset_mail.html.twig');

        $this->mailer->send($this->email);
    }

    private function setMail(Address $address, array $context): void
    {
        $this->email->to($address);
        $this->email->context($context);
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
        $this->email
            ->to($user->getMail())
            ->subject('Please Confirm your Email')
            ->htmlTemplate('registration/confirmation_email.html.twig');

        $token = $user->getHash();
        $completeUrl = $this->urlGenerator->generate($verifiedEmailRouteName,
            ['id' => $user->getId(), 'token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

        $context = $this->email->getContext();
        $context['signedUrl'] = $completeUrl;
        $this->email->context($context);

        $this->mailer->send($this->email);
    }

}