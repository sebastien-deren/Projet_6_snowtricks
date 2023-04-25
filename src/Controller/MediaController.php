<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Media;
use App\Form\MediaType;
use App\Repository\FigureRepository;
use App\Repository\MediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('figure/{slug}/media')]
#[IsGranted('ROLE_USER')]
class MediaController extends AbstractController
{

    #[Route('/new', name: 'app_media_new', methods: ['GET', 'POST'])]
    public function new(
        Request          $request,
        FigureRepository $repository,
        Figure           $figure
    ): Response
    {
        $medium = new Media();
        $form = $this->createForm(MediaType::class, $medium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $figure->addMedium($medium);
            $repository->save($figure, true);
            $this->addFlash('success','Vous avez ajouter un nouveau media  !');
            return $this->redirectToRoute('app_figure_show', ['slug' => $figure->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('media/new.html.twig', [
            'medium' => $medium,
            'figure' => $figure,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_media_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request         $request,
        Media           $medium,
        MediaRepository $mediaRepository
    ): Response
    {
        $form = $this->createForm(MediaType::class, $medium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mediaRepository->save($medium, true);
            $this->addFlash('success','Vous avez modifier un media  !');
            return $this->redirectToRoute('app_figure_show', ["slug" => $medium->getFigure()->getSlug()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('media/edit.html.twig', [
            'mediaEntity' => $medium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_media_delete', methods: ['POST'])]
    public function delete(
        Request         $request,
        Media           $medium,
        MediaRepository $mediaRepository
    ): Response
    {
        if ($this->isCsrfTokenValid('delete', $request->request->get('_token'))) {
            $this->addFlash('warning','Vous avez supprimer un media  !');
            $mediaRepository->remove($medium, true);
        }

        return $this->redirectToRoute('app_figure_show', ["slug" => $medium->getFigure()->getSlug()], Response::HTTP_SEE_OTHER);
    }
}
