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
        $users = $this->entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/userList.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route(path: '/user/{id}/edit', name: 'user_edit')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function edit(Request $request, User $user): Response
    {
        if ($request->isMethod('POST')) {
            $role = $request->request->get('role');
            if ($role === 'ROLE_ADMIN' || $role === 'ROLE_USER') {
                $user->setRoles([$role]);
                $this->entityManager->flush();
                $this->addFlash('success', 'Rôle mis à jour avec succès');
            }
            return $this->redirectToRoute('user_list');
        }

        return $this->render('admin/userEdit.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route(path: '/user/{id}/delete', name: 'user_delete', methods: ['POST'])]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès');
        }

        return $this->redirectToRoute('user_list');
    }

}