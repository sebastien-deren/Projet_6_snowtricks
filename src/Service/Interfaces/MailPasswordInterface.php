<?php

namespace App\Service\Interfaces;

use Symfony\Component\Mime\Address;

interface MailPasswordInterface
{
    public
    function sendResetPasswordMail(Address $address, array $context): void;
}