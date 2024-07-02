<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    #[Route('/inscription', name: 'inscription')]
    public function inscription(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder): Response
    {
        // Create a new User entity
        $user = new User();

        // Create the registration form using the UserType form class
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        // Handle form submission
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the password
            $encodedPassword = $encoder->encodePassword($user, $user->getPassword());

            // Set user details
            $user->setUsername($form->get('_username')->getData());
            $user->setEmail($form->get('_email')->getData());
            $user->setRoles([User::ROLE_USER]);
            $user->setPassword($encodedPassword);

            // Persist the new user entity to the database
            $em->persist($user);
            // Flush the changes to the database
            $em->flush();

            // Redirect to the login page
            return $this->redirectToRoute('login');
        }

        // Render the registration template with the form
        return $this->render('user/inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/dashboard', name: 'admin_dashboard')]
    public function adminDashboard(): Response
    {
        // Render the admin dashboard template
        return $this->render('admin/dashboard.html.twig');
    }
}
