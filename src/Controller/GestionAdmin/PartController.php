<?php

namespace App\Controller\GestionAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartController extends AbstractController
{
    #[Route('/part', name: 'app_part')]
    public function index(): Response
    {
        return $this->render('gestion/createEditPart.html.twig', [
            'controller_name' => 'PartController',
        ]);
    }

}
