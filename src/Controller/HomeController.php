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
        // Render the home page template
        return $this->render('home/index.html.twig', []);
    }

    #[Route('/faq', name: 'app_faq')]
    public function showFAQ(): Response
    {
        // Render the FAQ page template
        return $this->render('navigation/faq.html.twig', []);
    }

    #[Route('/billeterie', name: 'app_billeterie')]
    public function showBilleterie(): Response
    {
        // Render the billeterie page template
        return $this->render('navigation/billeterie.html.twig', []);
    }
}
