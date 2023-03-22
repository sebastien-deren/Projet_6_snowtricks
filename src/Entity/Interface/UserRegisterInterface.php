<?php

namespace App\Entity\Interface;

interface UserRegisterInterface
{
public function getUsername():?string;
public function getId():?int;
}