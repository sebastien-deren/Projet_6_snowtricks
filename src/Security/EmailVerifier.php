<?php

namespace App\Security;

use App\Entity\User;
use App\Service\VerifyEmailHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
/*
 * CANNOT USE SYMFONYCASTS BUNDLE (PROJECT REQUIREMENTS)
 * use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
 * use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
 */


class EmailVerifier
{
    public function __construct(

        private VerifyEmailHelper $helper,
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, User $user): void
    {

        $email = (new TemplatedEmail())
            ->from(new Address('mailer@snowtricks.com', 'Snowtricks mailer'))
            ->to($user->getMail())
            ->subject('Please Confirm your Email')
            ->htmlTemplate('registration/confirmation_email.html.twig');
        $completeUrl =$this->helper->createMailVerifier($verifyEmailRouteName,$user->getId(),$user->getUsername());

        $context = $email->getContext();
        $context['signedUrl'] = $completeUrl;
        $email->context($context);

        $this->mailer->send($email);
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, UserInterface $user): void
    {
        $this->helper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getMail());

        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
