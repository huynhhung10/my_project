<?php

namespace App\Controller;

use App\Entity\Genres;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/genres', name: 'genres')]
    public function indexGenre(): Response
    {
        $genres = $this->entityManager->getRepository(Genres::class)->findAll();
        return new Response(sprintf('Total genres: %d', count($genres)));
    }

    #[Route('/create-genre', name: 'create_genre', methods: ['GET', 'POST'])]
    public function createGenre(Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);

        if (isset($requestData['name'])) {
            $genre = new Genres();
            $genre->setName($requestData['name']);
            $this->entityManager->persist($genre);
            $this->entityManager->flush();
            $genreJson = $this->serializeGenre($genre);
            return new JsonResponse($genreJson);
        } else {
            return new Response('Invalid genre data', Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/update-genre/{id}', name: 'update_genre', methods: ['GET', 'PUT'])]
    public function updateGenre(Request $request, int $id): Response
    {
        $genre = $this->entityManager->getRepository(Genres::class)->find($id);

        if (!$genre) {
            return new Response('Genre not found', Response::HTTP_NOT_FOUND);
        }

        $requestData = json_decode($request->getContent(), true);

        if (!empty($requestData)) {
            if (isset($requestData['name'])) {
                $genre->setName($requestData['name']);
            }
            $this->entityManager->flush();
            $genreJson = $this->serializeGenre($genre);
            return new JsonResponse($genreJson);
        } else {
            return new Response('Invalid genre data', Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/delete-genre/{id}', name: 'delete_genre', methods: ['GET', 'DELETE'])]
    public function deleteGenre(int $id): Response
    {
        $genre = $this->entityManager->getRepository(Genres::class)->find($id);

        if (!$genre) {
            return new Response('Genre not found', Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($genre);
        $this->entityManager->flush();

        return new Response('Genre deleted successfully', Response::HTTP_OK);
    }

    private function serializeGenre(Genres $genre): array
    {
        return [
            'id' => $genre->getId(),
            'name' => $genre->getName(),
        ];
    }
}
