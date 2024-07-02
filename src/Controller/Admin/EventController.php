<?php

namespace App\Controller\Admin;

use App\Entity\Artiste;
use App\Entity\Scene;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
    #[Security("is_granted('ROLE_ADMIN')")]
    public function index(Request $request): Response
    {
        /**
         * Creates a new Concert entity and handles form submission.
         *
         * @param Request $request The request object.
         * @return Response The rendered template or a redirect response.
         */
        $concert = new Concert();
        $form = $this->createForm(ConcertType::class, $concert);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * Checks if an artiste with the same name already exists in the database.
             * If it does, sets the existing artiste to the concert.
             * Otherwise, persists the new artiste to the database.
             */
            $existingArtiste = $this->entityManager->getRepository(Artiste::class)->findOneBy([
                'name' => $form->get('artiste')->getData()->getName()
            ]);
    
            if ($existingArtiste) {
                $concert->setArtiste($existingArtiste);
            } else {
                $this->entityManager->persist($concert->getArtiste());
            }
    
            /**
             * Checks if a scene with the same number already exists in the database.
             * If it does, sets the existing scene to the concert.
             * Otherwise, persists the new scene to the database.
             */
            $existingScene = $this->entityManager->getRepository(Scene::class)->findOneBy([
                'number' => $form->get('scene')->getData()->getNumber()
            ]);
    
            if ($existingScene) {
                $concert->setScene($existingScene);
            } else {
                $this->entityManager->persist($concert->getScene());
            }
    
            // Persists the new concert to the database and flushes the changes.
            $this->entityManager->persist($concert);
            $this->entityManager->flush();
    
            // Redirects to the concert list page.
            return $this->redirectToRoute('app_event');
        }
    
        // Renders the template with the form and the list of concerts.
        return $this->render('gestion/createEditEvent.html.twig', [
            'form' => $form->createView(),
            'concerts' => $this->entityManager->getRepository(Concert::class)->findAll(),
        ]);
    }

    /**
 * Deletes a concert entity by its ID.
 *
 * @Route("/event/delete/{id}", name="app_delete_concert")
 * @Security("is_granted('ROLE_ADMIN')")
 *
 * @param int $id The ID of the concert to delete.
 * @return \Symfony\Component\HttpFoundation\Response Redirects to the concert list page.
 */
    #[Route('/event/delete/{id}', name: 'app_delete_concert')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function delete(int $id): Response
    {
        // Fetch the concert entity by its ID
        $concert = $this->entityManager->getRepository(Concert::class)->find($id);

        // If the concert entity exists, remove it from the database
        if ($concert) {
            $this->entityManager->remove($concert);
            $this->entityManager->flush();
        }

        // Redirect to the concert list page
        return $this->redirectToRoute('app_event');
    }

}
