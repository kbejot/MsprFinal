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
            /** @var UploadedFile $iconeFile */
            $iconeFile = $form['icone']->getData();

        if ($iconeFile)
        {
            $originalFilename = pathinfo($iconeFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$iconeFile->guessExtension();
            $part->setIcone($newFilename);
         }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($part);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_reseaux'); 
        }
    
        return $this->render('gestion/createEditPart.html.twig', [
            'form' => $form->createView(),
            'partenaires' => $parts
        ]);
    }

}
