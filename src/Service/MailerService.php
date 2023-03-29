<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailerService
{
    private TemplatedEmail $email ;
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly array $mailBot = ['address' => 'default@example.com', 'name' => 'Mail bot']
)
 {
     $this->email = (new TemplatedEmail())->from(new Address($this->mailBot['address'],$this->mailBot['name']));
 }

    /**
     * @param Address $address
     * @param array $context
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
public
function sendResetPasswordMail(Address $address,array $context):void
{
    $this->setMail($address,$context);
    $this->email
        ->subject('reset your password')
        ->htmlTemplate('reset_password/reset_mail.html.twig');

    $this->mailer->send($this->email);
}
private function setMail(Address $address,array $context):void{
        $this->email->to($address);
        $this->email->context($context);
}
}