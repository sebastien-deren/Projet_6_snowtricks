<?php

namespace App\Service\Interface;

use App\Entity\Interface\UserRegisterInterface;

interface UserVerifyMailServiceInterface
{
public function verifyEmail(UserRegisterInterface $user, string $token):void;
}