<?php

namespace App\Controller;

use App\Entity\Movies;
use App\Entity\Customers;
use App\Entity\Reviews;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CustomerReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Inherit docs.
 */
class UserPageController extends AbstractController
{
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
  public function index(): Response
  {
    $movies = $this->em->getRepository(Movies::class)->findAll();
    usort($movies, function ($a, $b) {
      return $b->getCreateat() <=> $a->getCreateat();
    });
    $new_movies = array_slice($movies, 0, 3);
    $ratings = [];
    $good_reviews = [];
    foreach ($movies as $movie) {
      $reviews = $movie->getReviews();
      $totalRating = 0;
      $reviewCount = count($reviews);
      if ($reviewCount > 0) {
        foreach ($reviews as $review) {
          if ($review->getRating() == 5) {
            $good_reviews[] = $review;
          }
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
      'good_reviews' => $good_reviews,
      'new_movies' => $new_movies
    ]);
  }

  /**
   * Inherit docs.
   */
  #[Route('/detail-product/{id}', name: 'detail_product')]
  public function detail($id, Request $request): Response
  {
    $movie = $this->em->getRepository(Movies::class)->find($id);
    $defaultImagePath = 'frontend/img/account/blank.jpg';
    $form = $this->createForm(CustomerReviewType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // Extract data from the form
      $formData = $form->getData();

      // Create a new Customer entity
      $customer = new Customers();
      $customer->setUsername($formData['username']);
      $customer->setEmail($formData['email']);
      $customer->setImg($formData['img']);
      $customer->setPhonenumber($formData['phonenumber']);

      // Create a new Review entity
      $review = new Reviews();
      $review->setReviewText($formData['reviewtext']);
      $review->setCustomer($customer);
      $review->setMovie($movie);

      // $review->setRating(5);
      $review->setRating($formData['rating']);


      // Persist and flush entities
      $this->em->persist($customer);
      $this->em->persist($review);
      $this->em->flush();
      // $entityManager->persist($customer);
      // $entityManager->persist($review);
      // $entityManager->flush();

      return $this->redirectToRoute('detail_product', ['id' => $id]); // Redirect to a success page
    }
    return $this->render('user_page/detail.html.twig', [
      'movie' => $movie,
      'form_review' => $form->createView(),
    ]);
  }
  /**
   * Search for movies.
   */
  #[Route('/user/search-movie', name: 'search-movie')]
  public function searchMovie(Request $request): Response
  {
    $searchQuery = $request->query->get('search_query');
    $searchField = $request->query->get('search_field');
    $queryBuilder = $this->em->createQueryBuilder();
    $queryBuilder
      ->select('m', 'g')
      ->from('App\Entity\Movies', 'm')
      ->leftJoin('m.Genre', 'g');
    if ($searchField === 'name') {
      $queryBuilder
        ->where("g.$searchField LIKE :searchQuery")
        ->setParameter('searchQuery', '%' . $searchQuery . '%');
    } elseif ($searchField === 'title') {
      $queryBuilder
        ->where("m.$searchField LIKE :searchQuery")
        ->setParameter('searchQuery', '%' . $searchQuery . '%');
    }
    $ratings = [];
    $movies = $queryBuilder->getQuery()->getResult();
    $formattedMovies = [];
    foreach ($movies as $movie) {
      $reviews = $movie->getReviews();
      $totalRating = 0;
      $reviewCount = count($reviews);
      if ($reviewCount > 0) {
        foreach ($reviews as $review) {
          $totalRating += $review->getRating();
        }
        $avgRating = $totalRating / $reviewCount;
        $ratings[$movie->getId()] = $avgRating;
      } else {
        $ratings[$movie->getId()] = 0;
      }
      $formattedMovies[] = [
        'id' => $movie->getId(),
        'title' => $movie->getTitle(),
        'img' => $movie->getImg(),
        'decription' => $movie->getDecription(),
        'genre' => [
          'id' => $movie->getGenre()->getId(),
          'name' => $movie->getGenre()->getName()
        ]
      ];
    }
    $data = [
      'movies' => $formattedMovies,
      'ratings' => $ratings
    ];
    return $this->json($data);
  }

  public function new(Request $request, $id): Response
  {


    return $this->render('review/new.html.twig', [
      'form_review' => $form->createView(),
    ]);
  }
}
