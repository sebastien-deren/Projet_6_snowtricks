<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DumpDataController extends AbstractController
{
    #[Route('/dump/data', name: 'app_dump_data')]
    public function index(UserRepository $userRepository, EntityManagerInterface $manager): Response
    {
        $users = $userRepository->findAll();
        foreach ($users as $user){
            $userRepository->remove($user);
            $manager->flush();
        }
        return $this->render('dump_data/index.html.twig', [
            'controller_name' => 'DumpDataController',
        ]);
    }
}
