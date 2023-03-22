<?php

namespace App\Service\Interface;


use App\Entity\Interface\UserRegisterInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

interface UserRegisterServiceInterface
{
 public function createNewUser(PasswordAuthenticatedUserInterface $user, string $plainPassword):void;
}