<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * User Controller.
 */
class UserController extends AbstractController {

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
 * Show all user.
 */
  #[Route('/users', name: 'user')]
  public function indexUser(): Response {
    $users = $this->em->getRepository(Users::class)->findAll();
    return new Response(sprintf('Total users: %d', count($users)));
  }

  /**
  * Create a new user.
  */
  #[Route('/create-user', name: 'create_user', methods: ['GET', 'POST'])]
  public function createUser(Request $request): Response {
    $requestData = json_decode($request->getContent(), TRUE);

    if (isset($requestData['username']) && isset($requestData['password']) && isset($requestData['email'])) {
      $user = new Users();
      $user->setUsername($requestData['username']);
      $user->setPassword($requestData['password']);
      $user->setEmail($requestData['email']);

      $this->em->persist($user);
      $this->em->flush();

      $userJson = $this->serializeUser($user);
      return new JsonResponse($userJson);
    }
    else {
      return new Response('Invalid user data', Response::HTTP_BAD_REQUEST);
    }
  }

  /**
  * Update a user.
  */
  #[Route('/update-user/{id}', name: 'update_user', methods: ['GET', 'PUT'])]
  public function updateUser(Request $request, int $id): Response {
    $user = $this->em->getRepository(Users::class)->find($id);

    if (!$user) {
      return new Response('User not found', Response::HTTP_NOT_FOUND);
    }

    $requestData = json_decode($request->getContent(), TRUE);

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

      $this->em->flush();

      $userJson = $this->serializeUser($user);
      return new JsonResponse($userJson);
    }
    else {
      return new Response('Invalid user data', Response::HTTP_BAD_REQUEST);
    }
  }

  /**
  * Delete a user.
  */
  #[Route('/delete-user/{id}', name: 'delete_user', methods: ['GET', 'DELETE'])]
  public function deleteUser(int $id): Response {
    $user = $this->em->getRepository(Users::class)->find($id);

    if (!$user) {
      return new Response('User not found', Response::HTTP_NOT_FOUND);
    }

    $this->em->remove($user);
    $this->em->flush();

    return new Response('User deleted successfully', Response::HTTP_OK);
  }

  /**
   * Serializes the User.
   */
  private function serializeUser(Users $user): array {
    return [
      'id' => $user->getId(),
      'username' => $user->getUsername(),
      'email' => $user->getEmail(),
      'password' => $user->getPassword(),
    ];
  }

}
