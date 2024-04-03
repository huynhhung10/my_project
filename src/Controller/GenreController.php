<?php

namespace App\Controller;

use App\Entity\Genres;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Inheric docs.
 */
class GenreController extends AbstractController {
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
  #[Route('/genre', name: 'genre')]
    public function index(): Response {
    $genres = $this->em->getRepository(Genres::class)->findAll();
    return new Response(sprintf('id new permission is %d', $genres));
    }

    /**
   * Create genre.
   */
    #[Route('/create-genre', name: 'create_genre')]
    public function create(Request $request) {
      $requestData = json_decode($request->getContent(), TRUE);
      // $requestData['name'] = "";
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
   * Update genre.
   */
    #[Route('/update-genre/{id}', name: 'create_genre')]
    public function update(Request $request, int $id): Response {
      $genre = $this->em->getRepository(Genres::class)->find($id);
      if ($genre === NULL) {
        return new Response('Genre not found', Response::HTTP_NOT_FOUND);
      }
      $requestData = json_decode($request->getContent(), TRUE);
      // $requestData['password'] = "tranminhthuc#1234";
      if (!empty($requestData)) {
        if (isset($requestData['name'])) {
          $genre->setName($requestData['name']);
        }
       
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
   * Update genre.
   */
    #[Route('/delete-genre/{id}', name: 'create_genre')]
    public function delete(int $id): Response {
      $genre = $this->em->getRepository(Genres::class)->find($id);
      if ($genre === NULL) {
        return new Response('Genres not found', Response::HTTP_NOT_FOUND);
      }
      $this->em->remove($genre);
      $this->em->flush();
      return new Response('Genres deleted successfully', Response::HTTP_OK);
    }

    /**
     * Serializes a Genre entity to JSON format.
     */
    private function serializeGenre(Genres $genre): array {
      return [
        'id' => $genre->getId(),
        'name' => $genre->getName(),
      ];
    }

}
