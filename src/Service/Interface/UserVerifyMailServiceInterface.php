<?php

namespace App\Service\Interface;

use App\Entity\Interface\UserRegisterInterface;
use App\Entity\Interface\VerifiableUserInterface;

interface UserVerifyMailServiceInterface
{
public function verifyEmail(UserRegisterInterface $user, string $token):void;
}