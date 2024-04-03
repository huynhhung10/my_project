<?php

namespace App\Controller;

use App\Entity\Movies;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Inheric docs.
 */
class MovieController extends AbstractController {
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
  #[Route('/movie', name: 'movie')]
    public function index(): Response {
    $movies = $this->em->getRepository(Movies::class)->findAll();
    return new Response(sprintf('id new permission is %d', $movies));
    }

    /**
   * Create movie.
   */
    #[Route('/create-movie', name: 'create_movie')]
    public function create(Request $request) {
      $requestData = json_decode($request->getContent(), TRUE);
      // $requestData['title'] = "";
      // $requestData['release_date'] = "";
      // $requestData['genre_id'] = "1";
      if (isset($requestData['title']) || isset($requestData['release_date']) || isset($requestData['genre_id'])) {
        $movie = new Movies();
        $movie->setTitle($requestData['title']);
        $movie->setReleaseDate($requestData['release_date']);
        $movie->setGenreId($requestData['genre_id']);
        $this->em->persist($movie);
        $this->em->flush();
        $movieJson = $this->serializeMovie($movie);
        return new JsonResponse($movieJson);
      }
      else {
        return new Response('Invalid movie data', Response::HTTP_BAD_REQUEST);
      }
    }

    /**
   * Update movie.
   */
    #[Route('/update-movie/{id}', name: 'create_movie')]
    public function update(Request $request, int $id): Response {
      $movie = $this->em->getRepository(Movies::class)->find($id);
      if ($movie === NULL) {
        return new Response('Movie not found', Response::HTTP_NOT_FOUND);
      }
      $requestData = json_decode($request->getContent(), TRUE);
      // $requestData['password'] = "tranminhthuc#1234";
      if (!empty($requestData)) {
        if (isset($requestData['title'])) {
          $movie->setTitle($requestData['title']);
        }
        if (isset($requestData['release_date'])) {
          $movie->setReleaseDate($requestData['release_date']);
        }
        if (isset($requestData['genre_id'])) {
          $movie->setGenreId($requestData['genre_id']);
          // dd($movie);
        }
        $this->em->persist($movie);
        $this->em->flush();
        $movieJson = $this->serializeMovie($movie);
        return new JsonResponse($movieJson);
      }
      else {
        return new Response('Invalid movie data', Response::HTTP_BAD_REQUEST);
      }
    }

    /**
   * Update movie.
   */
    #[Route('/delete-movie/{id}', name: 'create_movie')]
    public function delete(int $id): Response {
      $movie = $this->em->getRepository(Movies::class)->find($id);
      if ($movie === NULL) {
        return new Response('Movies not found', Response::HTTP_NOT_FOUND);
      }
      $this->em->remove($movie);
      $this->em->flush();
      return new Response('Movies deleted successfully', Response::HTTP_OK);
    }

    /**
     * Serializes a Movie entity to JSON format.
     */
    private function serializeMovie(Movies $movie): array {
      return [
        'id' => $movie->getId(),
        'title' => $movie->getTitle(),
        'release_date' => $movie->getReleaseDate(),
        'genre_id' => $movie->getGenreId(),
      ];
    }

}
