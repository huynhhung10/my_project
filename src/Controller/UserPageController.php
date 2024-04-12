<?php

namespace App\Controller;

use App\Entity\Movies;
use App\Entity\Reviews;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Inherit docs.
 */
class UserPageController extends AbstractController {
  /**
   * Entity manager.
   *
   * @var \Doctrine\ORM\EntityManagerInterface The entity manager instance It manages the database interactionsManages database interactions
   */
  private $em;

  /**
   * Constructor.
   *
   * @param \Doctrine\ORM\EntityManagerInterface $em
   *   The entity manager instance to manage database interactions.
   */
  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  /**
 * Inherit docs.
 */
  #[Route('/user-page', name: 'app_user_page')]
  public function index(): Response {
    $movies = $this->em->getRepository(Movies::class)->findAll();
    $ratings = [];
    foreach ($movies as $movie){
        $reviews = $movie->getReviews();
        $totalRating = 0;
        $reviewCount = count($reviews);
        if ($reviewCount > 0) {
            foreach ($reviews as $review){
                $totalRating += $review->getRating(); 
            }
            $avgRating = $totalRating / $reviewCount;
            $ratings[$movie->getId()] = $avgRating;
        } else {
            $ratings[$movie->getId()] = 0;
        }
    }
    return $this->render('user_page/index.html.twig', [
        'movies' => $movies,
        'ratings' => $ratings,
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
