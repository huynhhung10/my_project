<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin', defaults: ['name' => null], methods: ['GET', 'HEAD'])]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    #[Route('/admin/addlist', name: 'app_admin_add')]
    public function addadmin(): Response
    {
        return $this->render('admin/createadmin.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
