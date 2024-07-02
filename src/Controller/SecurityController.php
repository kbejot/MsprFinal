<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use App\Form\LoginFormType;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // Last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Create the login form with the last username
        $form = $this->createForm(LoginFormType::class, [
            '_username' => $lastUsername
        ]);

        // Handle the request with the form
        $form->handleRequest($request);
        
        // If form is submitted and valid and the user has ROLE_ADMIN, redirect to admin dashboard
        if ($form->isSubmitted() && $form->isValid() && $this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_dashboard');
        }
        
        // Render the login template with the form and error if any
        return $this->render('user/login.html.twig', [
            'loginForm' => $form->createView(),
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {
        // This method can be blank - it will be intercepted by the logout key on your firewall
        // Render the home page after logout
        return $this->render('home/index.html.twig');
    }
}
