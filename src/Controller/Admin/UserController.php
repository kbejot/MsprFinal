<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager){}

    #[Route(path: '/user/list', name: 'user_list')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function list(): Response
    {
        // Fetch all users from the database
        $users = $this->entityManager->getRepository(User::class)->findAll();

        // Render the template with the user list
        return $this->render('admin/userList.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route(path: '/user/{id}/edit', name: 'user_edit')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function edit(Request $request, User $user): Response
    {
        // Handle form submission for role update
        if ($request->isMethod('POST')) {
            $role = $request->request->get('role');
            if ($role === 'ROLE_ADMIN' || $role === 'ROLE_USER') {
                // Update user role
                $user->setRoles([$role]);
                $this->entityManager->flush();
                $this->addFlash('success', 'Rôle mis à jour avec succès');
            }
            // Redirect to the user list page
            return $this->redirectToRoute('user_list');
        }

        // Render the template with the user data for editing
        return $this->render('admin/userEdit.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route(path: '/user/{id}/delete', name: 'user_delete', methods: ['POST'])]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function delete(Request $request, User $user): Response
    {
        // Validate CSRF token before deleting the user
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            // Remove the user from the database
            $this->entityManager->remove($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès');
        }

        // Redirect to the user list page
        return $this->redirectToRoute('user_list');
    }
}
