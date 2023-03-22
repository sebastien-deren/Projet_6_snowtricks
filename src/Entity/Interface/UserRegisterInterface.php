<?php

namespace App\Entity\Interface;

interface UserRegisterInterface
{
public function setPassword(string $password);
public function setIsVerified(bool $verified);
public function getUsername():?string;
public function getId():?int;
}