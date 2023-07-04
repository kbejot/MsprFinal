<?php

// src/Controller/LoginController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $error = null;

        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');
            $password = $request->request->get('password');

            // Récupérez l'utilisateur correspondant au nom d'utilisateur (par exemple, à partir de la base de données)
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $username]);
                 {
                    // Authentifiez l'utilisateur
                    // ...
                    // Redirigez vers la page de succès ou une autre page appropriée
                    return $this->redirectToRoute('app_dashboard');
            
            $error = 'Nom d\'utilisateur ou mot de passe incorrect.';
        }

        return $this->render('login/index.html.twig', [
            'error' => $error,
        ]);
    }
}
}