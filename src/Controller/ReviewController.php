<?php

namespace App\Controller;

use App\Entity\Reviews;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Inheric docs.
 */
class ReviewController extends AbstractController {
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
  public function __construct(EntityManagerInterface $em) {
    $this->em = $em;
  }

  /**
 * Inheric docs.
 */
  #[Route('/review', name: 'review')]
    public function index(): Response {
    $reviews = $this->em->getRepository(Reviews::class)->findAll();
    return new Response(sprintf('All Review is %d', $reviews));
    }

    /**
   * Create review.
   */
    #[Route('/create-review', name: 'create_review')]
    public function create(Request $request) {
      $requestData = json_decode($request->getContent(), TRUE);
      if (isset($requestData['movie_id']) || isset($requestData['user_id']) || isset($requestData['review_text'])|| isset($requestData['rating'])) {
        $review = new Reviews();
        $review->setMovieId($requestData['movie_id']);
        $review->setUserId($requestData['user_id']);
        $review->setReviewText($requestData['review_text']);
        $review->setRating($requestData['rating']);
        $this->em->persist($review);
        $this->em->flush();
        $reviewData = [
            'id' => $review->getId(),
            'movie_id' => $review->getMovieId(),
            'review_id' => $review->getUserId(),
            'review_text' => $review->getReviewText(),
            'rating' => $review->getRating(),
        ];
    
        
        return new JsonResponse($reviewData);
      }
      else {
        return new Response('Invalid review data', Response::HTTP_BAD_REQUEST);
      }
    }

    /**
   * Update review.
   */
    #[Route('/update-review/{id}', name: 'update_review')]
    public function update(Request $request, int $id): Response {
      $review = $this->em->getRepository(Reviews::class)->find($id);
      if ($review === NULL) {
        return new Response('review not found', Response::HTTP_NOT_FOUND);
      }
      $requestData = json_decode($request->getContent(), TRUE);
      
      if (!empty($requestData)) {
        if (isset($requestData['movie_id'])) {
          $review->setMovieId($requestData['movie_id']);
        }
        if ( isset($requestData['user_id'])) {
          $review->setUserId($requestData['user_id']);
        }
        if (isset($requestData['review_text'])) {
          $review->setReviewText($requestData['review_text']);

        }
        if (isset($requestData['rating'])) {
            $review->  setRating($requestData['rating']);
        }
      
        $this->em->persist($review);
        $this->em->flush();
        $reviewData = [
            'id' => $review->getId(),
            'movie_id' => $review->getMovieId(),
            'review_id' => $review->getUserId(),
            'review_text' => $review->getReviewText(),
            'rating' => $review->getRating(),
        ];
    
        
        return new JsonResponse($reviewData);
      }
      else {
        return new Response('Invalid review data', Response::HTTP_BAD_REQUEST);
      }
    }

    /**
   * Update review.
   */
    #[Route('/delete-review/{id}', name: 'delete_review')]
    public function delete(int $id): Response {
      $review = $this->em->getRepository(reviews::class)->find($id);
      if ($review === NULL) {
        return new Response('review not found', Response::HTTP_NOT_FOUND);
      }
      $this->em->remove($review);
      $this->em->flush();
      return new Response('review deleted successfully', Response::HTTP_OK);
    }

  

}
