<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Inherit docs.
 */
class UserPageController extends AbstractController {

  /**
 * Inherit docs.
 */
  #[Route('/user-page', name: 'app_user_page')]
    public function index(): Response {
    return $this->render('user_page/index.html.twig', [
      'controller_name' => 'UserPageController',
    ]);
    }

    /**
   * Inherit docs.
   */
    #[Route('/detail-product', name: 'detail_product')]
    public function detail(): Response {
      return $this->render('user_page/detail.html.twig', [
        'controller_name' => 'UserPageController',
      ]);
    }

}
