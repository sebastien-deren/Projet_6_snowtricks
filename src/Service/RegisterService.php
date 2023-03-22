<?php

namespace App\Service;

use App\Entity\Interface\UserRegisterInterface;
use App\Entity\Interface\VerifiableUserInterface;
use App\Repository\UserRepository;
use App\Service\Interface\UserRegisterServiceInterface;
use App\Service\Interface\UserVerifyMailServiceInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class RegisterService implements UserRegisterServiceInterface, UserVerifyMailServiceInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher,
                                private readonly UserRepository              $userRepository)
    {
    }

    public
    function createNewUser(PasswordAuthenticatedUserInterface $user, string $plainPassword): void
    {
        $newPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $this->userRepository->upgradePassword($user, $newPassword);

    }

    public function verifyEmail(UserRegisterInterface $user, string $token): void
    {
        if (!hash_equals($token, hash('md5', $user->getId() . $user->getUsername()))) {
            throw new UnsupportedUserException('The token is not correct');
        }
        $this->userRepository->verifyUser($user, true);
    }


}