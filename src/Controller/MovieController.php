<?php

namespace App\Controller;

use App\Entity\Genres;
use App\Entity\Movies;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Movie class.
 */
class MovieController extends AbstractController
{

  /**
   * Entity manager.
   *
   * @var \Doctrine\ORM\EntityManagerInterface
   */
  private $em;

  /**
   * Constructor.
   *
   * @param \Doctrine\ORM\EntityManagerInterface $entityManager
   *   The entity manager instance.
   */
  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->em = $entityManager;
  }

  /**
   * Show all movies.
   */
  #[Route('/movies', name: 'movies')]
  public function indexMovie(): JsonResponse
  {
    $movies = $this->em->getRepository(Movies::class)->findAll();
    $movies_json = [];
    foreach ($movies as $movie) {
      $movies_json[] = $this->serializeMovie($movie);
    }
    return new JsonResponse($movies_json);
  }

  #[Route('/admin/addmovie', name: 'app_admin_addmovie')]
  public function addmovie_page(): Response
  {
    $users = new Movies();
    $form = $this->createForm(UserFormType::class, $users);
    // $form->handleRequest($request);

    return $this->render('admin/Movies/add_movie.html.twig', [
      'form' => $form->createView(),
      'controller_name' => 'AdminController',
    ]);
  }

  #[Route('/admin/editmovie', name: 'app_admin_editmovie')]
  public function editmovie_page(): Response
  {
    return $this->render('admin/Review/edit_movie.html.twig', [
      'controller_name' => 'AdminController',
    ]);
  }
  #[Route('/admin/allmovie', name: 'app_admin_allmovie')]
  public function listmovie_page(): Response
  {
    return $this->render('admin/Movie/all_movie.html.twig', [
      'controller_name' => 'AdminController',
    ]);
  }
  /**
   * Create a new movie.
   */
  #[Route('/create-movie', name: 'create_movie', methods: ['GET', 'POST'])]
  public function createMovie(Request $request): Response
  {
    $requestData = json_decode($request->getContent(), TRUE);

    if (isset($requestData['title']) && isset($requestData['genre_id'])) {
      $movie = new Movies();
      $genre = $this->em->getRepository(Genres::class)->find($requestData['genre_id']);
      if (!$genre) {
        return new Response('genre not found', Response::HTTP_NOT_FOUND);
      }
      $movie->setTitle($requestData['title']);
      $movie->setGenreId($genre);
      $this->em->persist($movie);
      $this->em->flush();

      $movieJson = $this->serializeMovie($movie);
      return new JsonResponse($movieJson);
    } else {
      return new Response('Invalid movie data', Response::HTTP_BAD_REQUEST);
    }
  }

  /**
   * Update a movie.
   */
  #[Route('/update-movie/{id}', name: 'update_movie', methods: ['GET', 'PUT'])]
  public function updateMovie(Request $request, int $id): Response
  {
    $movie = $this->em->getRepository(Movies::class)->find($id);

    if (!$movie) {
      return new Response('Movie not found', Response::HTTP_NOT_FOUND);
    }

    $requestData = json_decode($request->getContent(), TRUE);
    // $requestData['title'] = "update lan 2";
    // $requestData['genre_id'] = 2;
    if (!empty($requestData)) {
      if (isset($requestData['title'])) {
        $movie->setTitle($requestData['title']);
      }
      if (isset($requestData['genre_id'])) {
        $genre = $this->em->getRepository(Genres::class)->find($requestData['genre_id']);
        if (!$genre) {
          return new Response('genre not found', Response::HTTP_NOT_FOUND);
        }
        $movie->setGenreId($genre);
      }

      $this->em->flush();

      $movieJson = $this->serializeMovie($movie);
      return new JsonResponse($movieJson);
    } else {
      return new Response('Invalid movie data', Response::HTTP_BAD_REQUEST);
    }
  }

  /**
   * Delete a movie.
   */
  #[Route('/delete-movie/{id}', name: 'delete_movie', methods: ['GET', 'DELETE'])]
  public function deleteMovie(int $id): Response
  {
    $movie = $this->em->getRepository(Movies::class)->find($id);

    if (!$movie) {
      return new Response('Movie not found', Response::HTTP_NOT_FOUND);
    }

    $this->em->remove($movie);
    $this->em->flush();

    return new Response('Movie deleted successfully', Response::HTTP_OK);
  }

  /**
   * Serialize Movie.
   */
  private function serializeMovie(Movies $movie): array
  {
    return [
      'id' => $movie->getId(),
      'title' => $movie->getTitle(),
      'genre' => [
        'id' => $movie->getGenreId()->getId(),
        'name' => $movie->getGenreId()->getName(),
      ],
    ];
  }
}
