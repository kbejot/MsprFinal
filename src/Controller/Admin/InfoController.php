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
        $info = new Infos();
        $form = $this->createForm(InfosType::class, $info);
        $infos = $this->entityManager->getRepository(Infos::class)->findAll();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($info);
            $this->entityManager->flush();
    
            return $this->redirectToRoute('app_info');
        }
        return $this->render('gestion/createEditinfo.html.twig', [
            'form' => $form->createView(),
            'infos' => $infos,
        ]);
    }


    #[Route('/infos/delete/{id}', name: 'app_delete_info')]
    public function delete(int $id): Response
    {
        $info = $this->entityManager->getRepository(Infos::class)->find($id);

        if ($info) {
            $this->entityManager->remove($info);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_info');
    }

}
