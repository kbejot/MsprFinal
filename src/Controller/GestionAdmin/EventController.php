<?php

namespace App\Controller\GestionAdmin;

use App\Entity\Artiste;
use App\Entity\Scene;
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

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingArtiste = $this->entityManager->getRepository(Artiste::class)->findOneBy([
                'name' => $form->get('artiste')->getData()->getName()
            ]);

            $existingScene = $this->entityManager->getRepository(Scene::class)->findOneBy([
                'number' => $form->get('scene')->getData()->getNumber()
            ]);

            if ($existingArtiste) {
                $concert->setArtiste($existingArtiste);
            } else {
                $this->entityManager->persist($concert->getArtiste());
            }

            if ($existingScene) {
                $concert->setScene($existingScene);
            } else {
                $this->entityManager->persist($concert->getScene());
            }

            $this->entityManager->persist($concert);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_event');
        }

        return $this->render('gestion/createEditEvent.html.twig', [
            'form' => $form->createView(),
            'concerts' => $this->entityManager->getRepository(Concert::class)->findAll(),
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
