<?php

namespace App\Service;

use App\Entity\Interface\UserRegisterInterface;
use App\Repository\UserRepository;
use App\Service\Interface\UserRegisterServiceInterface;
use App\Service\Interface\UserVerifyMailServiceInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class RegisterService implements UserRegisterServiceInterface, UserVerifyMailServiceInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher,
                                private UserRepository              $userRepository,
                                private MailerInterface             $mailer,
                                private UrlGeneratorInterface $urlGenerator,
                                private array                       $botMail)
    {
    }

    /**
     * @param string $plainPassword
     * @return void
     */
    public
    function createNewUser(UserRegisterInterface $user, string $plainPassword): void
    {
        $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));
        $this->userRepository->save($user, true);

    }

    /**
     * @param string $verifiedEmailRouteName
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public
    function sendEMailConfirmation(UserRegisterInterface $user, string $verifiedEmailRouteName): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->botMail['address'], $this->botMail['name']))
            ->to($user->getMail())
            ->subject('Please Confirm your Email')
            ->htmlTemplate('registration/confirmation_email.html.twig');

        $token = hash('md5', $user->getId() . $user->getUsername());
        $completeUrl = $this->urlGenerator->generate($verifiedEmailRouteName,
            ['id' => $user->getId(), 'token' => $token],UrlGeneratorInterface::ABSOLUTE_URL);

        $context = $email->getContext();
        $context['signedUrl'] = $completeUrl;
        $email->context($context);

        $this->mailer->send($email);
    }

    public
    function verifyEmail(UserRegisterInterface $user, string $token): void
    {
        if (!hash_equals($token, hash('md5', $user->getId() . $user->getUsername()))) {
            throw new UnsupportedUserException('The token is not correct');
        }
        $user->setIsVerified(true);
        $this->userRepository->save($user, true);
    }


}