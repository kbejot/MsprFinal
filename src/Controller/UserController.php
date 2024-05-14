<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\User;
use App\Form\AdminType;
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
        $admin = new Admin();

        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encodedPassword = $encoder->encodePassword($admin, $admin->getPassword());
            $admin->setUsername($form->get('_username')->getData());
            $admin->setEmail($form->get('_email')->getData());
            $admin->setRoles([Admin::ROLE_USER]);
            $admin->setPassword($encodedPassword);

            $em->persist($admin);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/dashboard', name: 'admin_dashboard')]
    public function adminDashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }
}
