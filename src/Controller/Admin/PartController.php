<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Partenaires;
use App\Form\PartType;

class PartController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {

    }
    #[Route('/part', name: 'app_part')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function index(Request $request): Response
    {
        $part = new Partenaires();
        $form = $this->createForm(PartType::class, $part);
        $parts = $this->entityManager->getRepository(Partenaires::class)->findAll();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($part);
            $this->entityManager->flush();
    
            return $this->redirectToRoute('app_part'); 
        }
    
        return $this->render('gestion/createEditPart.html.twig', [
            'form' => $form->createView(),
            'partenaires' => $parts
        ]);
    }
    #[Route('/part/delete/{id}', name: 'app_delete_part')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function delete(int $id): Response
    {
        $part = $this->entityManager->getRepository(Partenaires::class)->find($id);

        if ($part) {
            $this->entityManager->remove($part);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_part');
    }

}
