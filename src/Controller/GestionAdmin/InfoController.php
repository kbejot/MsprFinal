<?php

namespace App\Controller\GestionAdmin;

use App\Entity\Infos;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\InfosType;

class InfoController extends AbstractController
{
    #[Route('/info', name: 'app_info')]
    public function index(Request $request, EntityManagerInterface $em): Response
        {
        $info = new Infos();
        $form = $this->createForm(InfosType::class, $info);
        $infos = $this->getDoctrine()->getRepository(Infos::class)->findAll();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($info);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_info');
        }
        return $this->render('gestion/createEditinfo.html.twig', [
            'form' => $form->createView(),
            'infos' => $infos,
        ]);
    }

    /**
    * @Route("/infos/delete/{id}", name="app_delete_info")
    */
public function delete(int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $info = $entityManager->getRepository(Infos::class)->find($id);

    if ($info) {
        $entityManager->remove($info);
        $entityManager->flush();
    }

    // Redirigez vers la page de gestion des concerts après la suppression.
    return $this->redirectToRoute('app_info');
}

}
