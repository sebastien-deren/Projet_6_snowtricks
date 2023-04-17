<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordResetRequestType;

use App\Form\PasswordResetType;
use App\Service\ResetPasswordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reset-password')]
class ResetPasswordController extends AbstractController
{

    public function __construct(private readonly ResetPasswordService $service)
    {
    }

    #[Route('', name: 'app_reset_password',methods:['GET','POST'])]
    public function index(Request $request): Response
    {
        //Create a Form type (probably only the mail address)
        $form = $this->createForm(PasswordResetRequestType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->get('username')->getData();
            try {
                $mail = $this->service->resetPasswordRequest($username, 'app_reset_password_mail');
            } catch (TransportExceptionInterface $e) {
                $error = $e->getMessage();
            }
            return $this->render('reset_password/resetEmailSent.html.twig' ,[
                'resetPasswordForm' => $form->createView(),
                'emailSent' => $mail ?? false,
                'username' => $username ?? false,
                'error' => $error ?? false,
            ]);
        }
        return $this->render('reset_password/index.html.twig', [
            'controller_name' => 'ResetPasswordController',
            'resetPasswordForm' => $form->createView(),
        ]);
    }

    #[Route('/email/{id}/{token}', name: 'app_reset_password_mail',methods:['GET','POST'])]
    public function resetFromMail(Request $request, User $user, string $token): Response
    {
        if (!hash_equals($token,$user->getHash())) {
            $this->redirect('app_homepage');
        }
        $form = $this->createForm(PasswordResetType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->upgradePassword($user, $form->get('plain_password')->getData());
            $this->addFlash('success','password has been updated');
            return  $this->redirectToRoute('app_login');
        }
        return $this->render('reset_password/resetPassword.html.twig', [
            'controller_name' => 'ResetPasswordController',
            'resetPasswordForm' => $form->createView(),
        ]);
    }
}
