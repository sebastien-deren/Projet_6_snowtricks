<?php

namespace App\Service\Interface;


use App\Entity\Interface\UserRegisterInterface;

interface UserRegisterServiceInterface
{
 public function createNewUser(UserRegisterInterface $user, string $plainPassword):void;
 public function sendEmailConfirmation(UserRegisterInterface $user, string $verifiedEmailRouteName);
}