<?php

namespace App\Controller\GestionAdmin;

use App\Entity\Reseaux;
use App\Form\ReseauxType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class ReseauxController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {}
    #[Route('/reseaux', name: 'app_reseaux')]
    public function index(Request $request): Response
    {
        $reseau = new Reseaux();
        $form = $this->createForm(ReseauxType::class, $reseau);
        $reseaux = $this->entityManager->getRepository(Reseaux::class)->findAll();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($reseau);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_reseaux');
        }
    
        return $this->render('gestion/createEditReseaux.html.twig', [
            'form' => $form->createView(),
            'reseaux' => $reseaux
        ]);
    }
    #[Route('/reseaux/delete/{id}', name: 'app_delete_reseaux')]
    public function delete(int $id): Response
    {
        $reseaux = $this->entityManager->getRepository(Reseaux::class)->find($id);

        if ($reseaux) {
            $this->entityManager->remove($reseaux);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_reseaux');
    }

}
