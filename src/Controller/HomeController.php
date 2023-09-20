<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\LoginFormType;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/faq', name: 'app_faq')]
    public function showFAQ(): Response
    {
        return $this->render('navigation/faq.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/billeterie', name: 'app_billeterie')]
    public function showBilleterie(): Response
    {
        return $this->render('navigation/billeterie.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
