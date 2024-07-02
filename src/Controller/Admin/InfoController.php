<?php

namespace App\Controller\Admin;

use App\Entity\Infos;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\InfosType;

class InfoController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {}
    #[Route('/info', name: 'app_info')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function index(Request $request): Response
    {
    // Create a new instance of Infos entity
    $info = new Infos();

    // Create a form for the Infos entity
    $form = $this->createForm(InfosType::class, $info);

    // Fetch all Infos entities from the database
    $infos = $this->entityManager->getRepository(Infos::class)->findAll();

    // Handle the form submission
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        // Persist the new Infos entity to the database
        $this->entityManager->persist($info);
        // Flush the changes to the database
        $this->entityManager->flush();

        // Redirect to the same page after successful submission
        return $this->redirectToRoute('app_info');
    }

    // Render the template with the form and fetched Infos entities
    return $this->render('gestion/createEditinfo.html.twig', [
        'form' => $form->createView(),
        'infos' => $infos,
    ]);
    }

    #[Route('/infos/delete/{id}', name: 'app_delete_info')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function delete(int $id): Response
    {
        // Fetch the Infos entity with the given ID from the database
        $info = $this->entityManager->getRepository(Infos::class)->find($id);
    
        // If the entity exists, remove it from the database
        if ($info) {
            $this->entityManager->remove($info);
            // Flush the changes to the database
            $this->entityManager->flush();
        }
    
        // Redirect to the 'app_info' route after deletion
        return $this->redirectToRoute('app_info');
    }

}
