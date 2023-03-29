<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Service\Interfaces\MailPasswordInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class ResetPasswordService
{


    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository              $userRepository,
        private readonly UrlGeneratorInterface       $urlGenerator,
        private readonly MailerService       $mailerService)
    {
    }

    /**
     * @param string $mail
     * @param string $addressName
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function ResetPasswordRequest(string $mail, string $addressName): void
    {
        try{

            $user = $this->userRepository->findOneBy(['mail' => $mail]) ?? throw new UserNotFoundException();
        }catch(UserNotFoundException $e){
            return;
        }
        $token = $user->getHash();
        $fqAddress = $this->urlGenerator->generate($addressName, ['id' => $user->getId(), 'token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
        $this->mailerService->sendResetPasswordMail(new Address($user->getMail(), $user->getUsername()), ['address' => $fqAddress]);
    }


    public
    function upgradePassword(PasswordAuthenticatedUserInterface $user, string $plainPassword): void
    {
        $newPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $this->userRepository->upgradePassword($user, $newPassword);

    }


}