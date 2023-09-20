<?php

namespace App\Controller\GestionAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Partenaires;
use App\Form\PartName;

class PartController extends AbstractController
{
    #[Route('/part', name: 'app_part')]
    public function index(Request $request): Response
    {
        $part = new Partenaires();
        $form = $this->createForm(PartName::class, $part);
        $parts = $this->getDoctrine()->getRepository(Partenaires::class)->findAll();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($part);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_part'); 
        }
    
        return $this->render('gestion/createEditPart.html.twig', [
            'form' => $form->createView(),
            'partenaires' => $parts
        ]);
    }
    /**
     * @Route("/part/delete/{id}", name="app_delete_part")
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $part = $entityManager->getRepository(Partenaires::class)->find($id);

        if ($part) {
            $entityManager->remove($part);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_part');
    }

}
