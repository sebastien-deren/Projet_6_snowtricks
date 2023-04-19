<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Form\FigureType;
use App\Repository\FigureRepository;
use App\Service\FigureService;
use App\Entity\Message;
use App\Form\MessageType;
use App\Service\CreateMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/figure')]
class FigureController extends AbstractController
{
    #[Route('', name: 'app_figure_index', methods: ['GET'])]

    public function index(FigureRepository $figureRepository): Response
    {
        return $this->render('figure/index.html.twig', [
            'figures' => $figureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_figure_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, FigureService $service): Response
    {
        $figure = new Figure();
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $service->saveFigure($figure);

            return $this->redirectToRoute('app_figure_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('figure/new.html.twig', [
            'figure' => $figure,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_figure_show', methods: ['GET','POST'])]
    public function show(Request $request, Figure $figure, CreateMessage $createMessage): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class,$message);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->isGranted('IS_AUTHENTICATED');
            $createMessage($message,$this->getUser(),$figure);
        }
        return $this->render('figure/show.html.twig', [
            'figure' => $figure,
            'formMessage' => $form,
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_figure_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Figure $figure, FigureService $service): Response
    {
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->saveFigure($figure, true);

            return $this->redirectToRoute('app_figure_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('figure/edit.html.twig', [
            'figure' => $figure,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_figure_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Figure $figure, FigureService $service): Response
    {
        if ($this->isCsrfTokenValid('delete', $request->request->get('_token'))) {
            $service->removeFigure($figure, true);
        }

        return $this->redirectToRoute('app_figure_index', [], Response::HTTP_SEE_OTHER);
    }
}
