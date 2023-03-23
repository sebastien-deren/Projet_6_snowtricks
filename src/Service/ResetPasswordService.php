<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class ResetPasswordService
{


    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository        $userRepository,
        private UrlGeneratorInterface $urlGenerator,
        private MailerInterface       $mailer,
        private array                 $mailBot = ['address' => 'default@example.com', 'name' => 'Mail bot'])
    {
    }

    public function ResetPasswordRequest(string $mail, string $addressName): void
    {
        $user = $this->userRepository->findOneBy(['mail' => $mail]) ?? throw new UserNotFoundException();
        $token = hash('md5', $user->getId() . $user->getUsername());
        $fqan = $this->urlGenerator->generate($addressName, ['id' => $user->getId(), 'token' => $token],UrlGeneratorInterface::ABSOLUTE_URL);
        $this->sendmail($user, $fqan);
    }

    private function sendmail(User $user, string $fqan): void
    {

        $mail = (new TemplatedEmail())
            ->from($this->mailBot['address'])
            ->to(new address($user->getMail(), $user->getUsername()))
            ->subject('reset your password')
            ->htmlTemplate('reset_password/reset_mail.html.twig');

        $context = $mail->getContext();
        $context['address']= $fqan;
        $mail->context($context);
        $this->mailer->send($mail);
    }

    public
    function upgradePassword(PasswordAuthenticatedUserInterface $user, string $plainPassword): void
    {
        $newPassword = $this->passwordHasher->hashPassword($user,$plainPassword);
        $this->userRepository->upgradePassword($user, $newPassword);

    }


}