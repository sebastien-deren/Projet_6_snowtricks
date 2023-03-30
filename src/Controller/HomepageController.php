<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/homepage', name: 'app_homepage', methods: ['GET', 'POST'])]
    #[Route('/', name: 'app_homepage', methods: ['GET', 'POST'])]

    public function index(Request $request, MessageRepository $messageRepository, UserRepository $userRepository): Response
    {
        $messages = $messageRepository->findBy(["figure" => null]);
        $message = (new Message())
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUser($this->getUser() ?? $userRepository->findOneBy(["username" => 'green']))
            ->setFigure(null);
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //to move to a service or repo

            $messageRepository->save($message, true);

        }

        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'messages' => $messages,
            'formMessage' => $form
        ]);
    }
}
