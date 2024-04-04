<?php

namespace App\Controller;

use App\Entity\Genres;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Genre class.
 */
class GenreController extends AbstractController {

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
  public function __construct(EntityManagerInterface $entityManager) {
    $this->em = $entityManager;
  }

  /**
  * Show all genres.
  */
  #[Route('/genres', name: 'genres')]
  public function indexGenre(): Response {
    $genres = $this->em->getRepository(Genres::class)->findAll();
    return new Response(sprintf('Total genres: %d', count($genres)));
  }

  /**
  * Creates a new Genre.
  */
  #[Route('/create-genre', name: 'create_genre', methods: ['GET', 'POST'])]
  public function createGenre(Request $request): Response {
    $requestData = json_decode($request->getContent(), TRUE);

    if (isset($requestData['name'])) {
      $genre = new Genres();
      $genre->setName($requestData['name']);
      $this->em->persist($genre);
      $this->em->flush();
      $genreJson = $this->serializeGenre($genre);
      return new JsonResponse($genreJson);
    }
    else {
      return new Response('Invalid genre data', Response::HTTP_BAD_REQUEST);
    }
  }

  /**
  * Update a Genre.
  */
  #[Route('/update-genre/{id}', name: 'update_genre', methods: ['GET', 'PUT'])]
  public function updateGenre(Request $request, int $id): Response {
    $genre = $this->em->getRepository(Genres::class)->find($id);

    if (!$genre) {
      return new Response('Genre not found', Response::HTTP_NOT_FOUND);
    }

    $requestData = json_decode($request->getContent(), TRUE);

    if (!empty($requestData)) {
      if (isset($requestData['name'])) {
        $genre->setName($requestData['name']);
      }
      $this->em->flush();
      $genreJson = $this->serializeGenre($genre);
      return new JsonResponse($genreJson);
    }
    else {
      return new Response('Invalid genre data', Response::HTTP_BAD_REQUEST);
    }
  }

  /**
  * Delete a genre.
  */
  #[Route('/delete-genre/{id}', name: 'delete_genre', methods: ['GET', 'DELETE'])]
  public function deleteGenre(int $id): Response {
    $genre = $this->em->getRepository(Genres::class)->find($id);

    if (!$genre) {
      return new Response('Genre not found', Response::HTTP_NOT_FOUND);
    }

    $this->em->remove($genre);
    $this->em->flush();

    return new Response('Genre deleted successfully', Response::HTTP_OK);
  }

  /**
   * Serializes the Genre.
   */
  private function serializeGenre(Genres $genre): array {
    return [
      'id' => $genre->getId(),
      'name' => $genre->getName(),
    ];
  }

}
