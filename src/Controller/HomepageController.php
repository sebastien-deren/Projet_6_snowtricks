<?php

namespace App\Controller;

use App\Repository\FigureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage', methods: 'GET')]

    public function index(FigureRepository $repository): Response
    {
        return $this->render('homepage/index.html.twig', [
            'figures' => $repository->findAll(),
        ]);
    }
}
