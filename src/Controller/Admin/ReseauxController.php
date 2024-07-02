<?php

namespace App\Controller\Admin;

use App\Entity\Reseaux;
use App\Form\ReseauxType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReseauxController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {}

    #[Route('/reseaux', name: 'app_reseaux')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function index(Request $request): Response
    {
        // Create a new Reseaux entity
        $reseau = new Reseaux();
    
        // Create a form using the ReseauxType form class
        $form = $this->createForm(ReseauxType::class, $reseau);
    
        // Fetch all reseaux from the database
        $reseaux = $this->entityManager->getRepository(Reseaux::class)->findAll();
    
        // Handle form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the new reseau entity to the database
            $this->entityManager->persist($reseau);
    
            // Flush the changes to the database
            $this->entityManager->flush();
    
            // Redirect to the reseaux list page
            return $this->redirectToRoute('app_reseaux');
        }
    
        // Render the template with the form and reseaux list
        return $this->render('gestion/createEditReseaux.html.twig', [
            'form' => $form->createView(),
            'reseaux' => $reseaux
        ]);
    }

    #[Route('/reseaux/delete/{id}', name: 'app_delete_reseaux')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function delete(int $id): Response
    {
        // Find the reseau by its ID
        $reseaux = $this->entityManager->getRepository(Reseaux::class)->find($id);
    
        // If the reseau exists, remove it from the database
        if ($reseaux) {
            $this->entityManager->remove($reseaux);
            $this->entityManager->flush();
        }
    
        // Redirect to the reseaux list page
        return $this->redirectToRoute('app_reseaux');
    }
}
