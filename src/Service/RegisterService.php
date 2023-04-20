<?php

namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class RegisterService
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher,
                                private readonly UserRepository              $userRepository)
    {
    }

    public
    function createNewUser(User $user, string $plainPassword): void
    {
        $newPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
        $user->setPhoto("https://avatar.vercel.sh/".$user->getUsername());
        $this->userRepository->upgradePassword($user, $newPassword);

    }

    public function verifyEmail(User $user, string $token): void
    {
        if (!hash_equals($token, $user->getHash())) {
            throw new UnsupportedUserException('The token is not correct');
        }
        $this->userRepository->verifyUser($user);
    }


}
