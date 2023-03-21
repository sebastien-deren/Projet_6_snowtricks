<?php

namespace App\Service;

use App\DTO\EmailVerifierDTO;
use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\RememberMe\TokenVerifierInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class VerifyEmailHelper
{
    public function __construct(private UrlGeneratorInterface $urlGenerator){
    }

    private function createRegisterLink(string $fqrn,$userId,$token ):string{
        //this is the url to go verify our registration
        return $this->urlGenerator->generate($fqrn,['id'=>$userId,'token'=>$token],UrlGeneratorInterface::ABSOLUTE_URL);

    }
    public function createMailVerifier(string $fqrn, int $userId ,string $username):string{
        $token=$this->createToken($userId,$username);
        return $this->createRegisterLink($fqrn,$userId,$token);


    }
    private function createToken(string $userId,string $username):string{
         return hash('md5',$userId.$username);
    }

}