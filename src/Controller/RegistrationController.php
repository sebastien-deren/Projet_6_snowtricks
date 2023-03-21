<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/*
 * use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
 * CANNOT USE THIS BUNDLE AS IT IS FORBIDDEN IN THE PROJECT
 */

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            //do we keep this here ? directly to entity
            $plainPassword = $form->get('plainPassword')->getData();
            $userRepository->registerNewUser($user, $plainPassword, true);

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('mailer@snowtricks.com', 'Snowtricks mailer'))
                    ->to($user->getMail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            return $this->redirectToRoute('_profiler_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email/{id}', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, User $user, EntityManagerInterface $manager): Response
    {
        $token = $request->get('token');
        if (null === $token) {
            return $this->redirectToRoute('app_register');
        }

        try {
            if (!hash_equals($token, hash('md5', $user->getId() . $user->getUsername()))) {
                throw new UnsupportedUserException('The token is not correct');
            }
            $user->setIsVerified(true);
            $manager->flush();

            dd($user);
            //return $this->redirectToRoute('_profiler_home');
        } catch (UnsupportedUserException $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_register');

        }
        // validate email confirmation link, sets User::isVerified=true and persists


        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }

}
