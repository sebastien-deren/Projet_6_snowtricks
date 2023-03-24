<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordResetRequestType;

use App\Form\PasswordResetType;
use App\Repository\UserRepository;
use App\Service\ResetPasswordService;
use PHPUnit\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reset-password')]
class ResetPasswordController extends AbstractController
{

    public function __construct(private ResetPasswordService $service)
    {
    }

    #[Route('', name: 'app_reset_password')]
    public function index(Request $request): Response
    {
        //Create a Form type (probably only the mail adress)
        $form = $this->createForm(PasswordResetRequestType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mail = $form->get('mail')->getData();
            try {
                $this->service->ResetPasswordRequest($mail, 'app_reset_password_mail');
            } catch (TransportExceptionInterface $e) {
                $error = $e->getMessage();
            }
        }
        return $this->render('reset_password/index.html.twig', [
            'controller_name' => 'ResetPasswordController',
            'resetPasswordRequestForm' => $form->createView(),
            'emailSent' => $mail ?? false,
            'error' => $error ?? false,
        ]);
    }

    #[Route('/email/{id}/{token}', name: 'app_reset_password_mail')]
    public function resetFromMail(Request $request, User $user, string $token): Response
    {
        if (!hash_equals($token, hash('md5', $user->getId() . $user->getUsername()))) {
            $this->redirect('app_homepage');
        }
        $form = $this->createForm(PasswordResetType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->upgradePassword($user, $form->get('plain_password')->getData());
            dd($user);
        }
        return $this->render('reset_password/index.html.twig', [
            'controller_name' => 'ResetPasswordController',
            'resetPasswordForm' => $form->createView(),
        ]);

        //return $this->redirect('app_login');
    }
}
