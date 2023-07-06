<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/welcome', name: 'app_welcome')]
    public function ifLog(): Response
    {
        return $this->render('home/welcome.html.twig', [
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
    #[Route('/login', name: 'app_login')]
    public function showLogin(): Response
    {
        return $this->render('user/login.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
