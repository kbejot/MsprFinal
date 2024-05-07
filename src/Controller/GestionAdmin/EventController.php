<?php

namespace App\Controller\GestionAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Concert;
use App\Form\ConcertType;

class EventController extends AbstractController
    
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {}

    #[Route('/event', name: 'app_event')]
    public function index(Request $request): Response
{
    $concert = new Concert();
    $form = $this->createForm(ConcertType::class, $concert);
    $concerts = $this->entityManager->getRepository(Concert::class)->findAll();
    
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $this->entityManager->persist($concert);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_event'); // or another route if needed
    }

    return $this->render('gestion/createEditEvent.html.twig', [
        'form' => $form->createView(),
        'concerts' => $concerts
    ]);
}

    #[Route('/event/delete/{id}', name: 'app_delete_concert')]

public function delete(int $id): Response
{
    $concert = $this->entityManager->getRepository(Concert::class)->find($id);

    if ($concert) {
        $this->entityManager->remove($concert);
        $this->entityManager->flush();
    }

    return $this->redirectToRoute('app_event');
}

}
