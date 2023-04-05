<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Service\Interface\ConfirmEmailInterface;
use App\Service\Interface\UserRegisterServiceInterface;
use App\Service\Interface\UserVerifyMailServiceInterface;
use App\Service\MailerService;
use App\Service\RegisterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/*
 * use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
 * CANNOT USE THIS BUNDLE AS IT IS FORBIDDEN IN THE PROJECT
 */

class RegistrationController extends AbstractController
{


    #[Route('/register', name: 'app_register')]
    //create a register Interface
    public function register(Request $request, RegisterService $registerService, MailerService $confirmEmail): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $registerService->createNewUser($user, $plainPassword);
            try {
                $confirmEmail->sendConfirmation($user, 'app_verify_email');
            } catch (TransportExceptionInterface $transportException) {
                $this->addFlash('error', $transportException->getMessage());
                return $this->redirectToRoute('app_register');
            }

            return $this->redirectToRoute('_profiler_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email/{id}/{token}', name: 'app_verify_email')]
    public function verifyUserEmail(User $user, string $token, RegisterService $registerService): Response
    {

        try {
            $registerService->verifyEmail($user, $token);
        } catch (UnsupportedUserException $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');
        return $this->redirectToRoute('app_homepage');
    }

}
