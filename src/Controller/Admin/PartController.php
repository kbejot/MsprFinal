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
        // Create a new Partenaires entity
        $part = new Partenaires();
    
        // Create a form using the PartType form class
        $form = $this->createForm(PartType::class, $part);
    
        // Fetch all partenaires from the database
        $parts = $this->entityManager->getRepository(Partenaires::class)->findAll();
    
        // Handle form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
    
            // Persist the new partenaire entity to the database
            $this->entityManager->persist($part);
    
            // Flush the changes to the database
            $this->entityManager->flush();
    
            // Redirect to the partenaire list page
            return $this->redirectToRoute('app_part'); 
        }
    
        // Render the template with the form and partenaire list
        return $this->render('gestion/createEditPart.html.twig', [
            'form' => $form->createView(),
            'partenaires' => $parts
        ]);
    }
    #[Route('/part/delete/{id}', name: 'app_delete_part')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function delete(int $id): Response
    {
        // Find the partenaire by its ID
        $part = $this->entityManager->getRepository(Partenaires::class)->find($id);
    
        // If the partenaire exists, remove it from the database
        if ($part) {
            $this->entityManager->remove($part);
            $this->entityManager->flush();
        }
    
        // Redirect to the partenaire list page
        return $this->redirectToRoute('app_part');
    }

}
