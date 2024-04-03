<?php

namespace App\Controller;

use App\Entity\Movies;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/movies', name: 'movies')]
    public function indexMovie(): Response
    {
        $movies = $this->entityManager->getRepository(Movies::class)->findAll();
        return new Response(sprintf('Total movies: %d', count($movies)));
    }

    #[Route('/create-movie', name: 'create_movie', methods: ['GET', 'POST'])]
    public function createMovie(Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);

        if (isset($requestData['title']) && isset($requestData['release_date']) && isset($requestData['genre_id'])) {
            $movie = new Movies();
            $movie->setTitle($requestData['title']);
            $movie->setGenreId($requestData['genre_id']);
            $this->entityManager->persist($movie);
            $this->entityManager->flush();

            $movieJson = $this->serializeMovie($movie);
            return new JsonResponse($movieJson);
        } else {
            return new Response('Invalid movie data', Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/update-movie/{id}', name: 'update_movie', methods: ['GET', 'PUT'])]
    public function updateMovie(Request $request, int $id): Response
    {
        $movie = $this->entityManager->getRepository(Movies::class)->find($id);

        if (!$movie) {
            return new Response('Movie not found', Response::HTTP_NOT_FOUND);
        }

        $requestData = json_decode($request->getContent(), true);

        if (!empty($requestData)) {
            if (isset($requestData['title'])) {
                $movie->setTitle($requestData['title']);
            }
            if (isset($requestData['release_date'])) {
                $movie->setReleaseDate($requestData['release_date']);
            }
            if (isset($requestData['genre_id'])) {
                $movie->setGenreId($requestData['genre_id']);
            }

            $this->entityManager->flush();

            $movieJson = $this->serializeMovie($movie);
            return new JsonResponse($movieJson);
        } else {
            return new Response('Invalid movie data', Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/delete-movie/{id}', name: 'delete_movie', methods: ['GET', 'DELETE'])]
    public function deleteMovie(int $id): Response
    {
        $movie = $this->entityManager->getRepository(Movies::class)->find($id);

        if (!$movie) {
            return new Response('Movie not found', Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($movie);
        $this->entityManager->flush();

        return new Response('Movie deleted successfully', Response::HTTP_OK);
    }

    private function serializeMovie(Movies $movie): array
    {
        return [
            'id' => $movie->getId(),
            'title' => $movie->getTitle(),
            'genre_id' => $movie->getGenreId(),
        ];
    }
}