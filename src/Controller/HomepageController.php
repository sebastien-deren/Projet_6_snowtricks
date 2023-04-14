<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Service\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/homepage', name: 'app_homepage', methods: ['GET', 'POST'])]
    #[Route('/', name: 'app_homepage', methods: ['GET', 'POST'])]

    public function index(Request $request,MessageService $messageService): Response
    {

        /*now we get all we'll need to make it so not all comment show either by hiding them with js or getting them little by little
        with a simple ajax request*/
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->isGranted('IS_AUTHENTICATED');
            $messageService->create($message,$this->getUser());
        }
        $messages = $messageService->DisplayFront();
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'messages' => $messages,
            'formMessage' => $form,
        ]);
    }
}
