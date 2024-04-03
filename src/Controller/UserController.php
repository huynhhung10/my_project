<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/user', name: 'user')]
    public function indexUser(): Response
    {
        $users = $this->entityManager->getRepository(Users::class)->findAll();
        return new Response(sprintf('Total users: %d', count($users)));
    }

    #[Route('/create-user', name: 'create_user', methods: ['GET', 'POST'])]
    public function createUser(Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);

        if (isset($requestData['username']) && isset($requestData['password']) && isset($requestData['email'])) {
            $user = new Users();
            $user->setUsername($requestData['username']);
            $user->setPassword($requestData['password']);
            $user->setEmail($requestData['email']);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $userJson = $this->serializeUser($user);
            return new JsonResponse($userJson);
        } else {
            return new Response('Invalid user data', Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/update-user/{id}', name: 'update_user', methods: ['GET', 'PUT'])]
    public function updateUser(Request $request, int $id): Response
    {
        $user = $this->entityManager->getRepository(Users::class)->find($id);

        if (!$user) {
            return new Response('User not found', Response::HTTP_NOT_FOUND);
        }

        $requestData = json_decode($request->getContent(), true);

        if (!empty($requestData)) {
            if (isset($requestData['username'])) {
                $user->setUsername($requestData['username']);
            }
            if (isset($requestData['email'])) {
                $user->setEmail($requestData['email']);
            }
            if (isset($requestData['password'])) {
                $user->setPassword($requestData['password']);
            }

            $this->entityManager->flush();

            $userJson = $this->serializeUser($user);
            return new JsonResponse($userJson);
        } else {
            return new Response('Invalid user data', Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/delete-user/{id}', name: 'delete_user', methods: ['GET', 'DELETE'])]
    public function deleteUser(int $id): Response
    {
        $user = $this->entityManager->getRepository(Users::class)->find($id);

        if (!$user) {
            return new Response('User not found', Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new Response('User deleted successfully', Response::HTTP_OK);
    }

    private function serializeUser(Users $user): array
    {
        return [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
        ];
    }
}