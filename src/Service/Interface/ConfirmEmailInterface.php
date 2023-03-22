<?php

namespace App\Service\Interface;

use App\Entity\Interface\UserRegisterInterface;

interface ConfirmEmailInterface
{
 public function sendConfirmation(UserRegisterInterface $user, string $verifiedEmailRouteName):void;
}