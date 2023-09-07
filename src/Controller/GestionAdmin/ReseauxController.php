<?php

namespace App\Controller\GestionAdmin;

use App\Entity\Reseaux;
use App\Form\ReseauxName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
Use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ReseauxController extends AbstractController
{
    #[Route('/reseaux', name: 'app_reseaux')]
    public function index(Request $request): Response
    {
        $reseau = new Reseaux();
        $form = $this->createForm(ReseauxName::class, $reseau);
        $reseaux = $this->getDoctrine()->getRepository(Reseaux::class)->findAll();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $iconeFile */
            $iconeFile = $form['icone']->getData();

        if ($iconeFile) {
            $originalFilename = pathinfo($iconeFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$iconeFile->guessExtension();

        try {
            $iconeFile->move(
                $this->getParameter('images_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

    $reseau->setIcone($newFilename);
}
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reseau);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_reseaux'); 
        }
    
        return $this->render('gestion/createEditReseaux.html.twig', [
            'form' => $form->createView(),
            'reseaux' => $reseaux
        ]);
    }
    /**
     * @Route("/reseaux/delete/{id}", name="app_delete_reseaux")
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reseaux = $entityManager->getRepository(Reseaux::class)->find($id);

        if ($reseaux) {
            $entityManager->remove($reseaux);
            $entityManager->flush();
        }

        // Redirigez vers la page de gestion des concerts après la suppression.
        return $this->redirectToRoute('app_reseaux');
    }

}