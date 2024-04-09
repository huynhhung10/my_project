<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExceptionController extends AbstractController
{
    #[Route('/exception', name: 'app_exception')]
    public function index(): Response
    {
        return $this->render('exception/index.html.twig', [
            'controller_name' => 'ExceptionController',
        ]);
    }
}
