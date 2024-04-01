<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Inheric docs.
 */
class UserController extends AbstractController
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
   * Inheric docs.
   */
  #[Route('/user', name: 'user')]
  public function index(): Response
  {
    $users = $this->em->getRepository(Users::class)->findAll();
    return new Response(sprintf('id new permission is %d', $users));
  }

  #[Route('/admin/adduser', name: 'app_admin_adduser')]
  public function adduser_page(): Response
  {
    return $this->render('admin/User/add_user.html.twig', [
      'controller_name' => 'AdminController',
    ]);
  }
  #[Route('/admin/edituser', name: 'app_admin_edituser')]
  public function edituser_page(): Response
  {
    return $this->render('admin/User/edit_user.html.twig', [
      'controller_name' => 'AdminController',
    ]);
  }
  #[Route('/admin/alluser', name: 'app_admin_alluser')]
  public function listuser_page(): Response
  {
    return $this->render('admin/User/all_user.html.twig', [
      'controller_name' => 'AdminController',
    ]);
  }
  /**
   * Create user.
   */
  #[Route('/create-user', name: 'create_user')]
  public function create(Request $request)
  {
    $requestData = json_decode($request->getContent(), TRUE);
    // $requestData['username'] = "tranminhthuc";
    // $requestData['password'] = "tranminhthuc#123";
    // $requestData['email'] = "tranminhthuc@gmail.com";
    if (isset($requestData['username']) || isset($requestData['password']) || isset($requestData['email'])) {
      $user = new Users();
      $user->setUsername($requestData['username']);
      $user->setPassword($requestData['password']);
      $user->setEmail($requestData['email']);
      $this->em->persist($user);
      $this->em->flush();
      $userJson = $this->serializeUser($user);
      return new JsonResponse($userJson);
    } else {
      return new Response('Invalid user data', Response::HTTP_BAD_REQUEST);
    }
  }

  /**
   * Update user.
   */
  #[Route('/update-user/{id}', name: 'create_user')]
  public function update(Request $request, int $id): Response
  {
    $user = $this->em->getRepository(Users::class)->find($id);
    if ($user === NULL) {
      return new Response('User not found', Response::HTTP_NOT_FOUND);
    }
    $requestData = json_decode($request->getContent(), TRUE);
    // $requestData['password'] = "tranminhthuc#1234";
    if (!empty($requestData)) {
      if (isset($requestData['username'])) {
        $user->setUsername($requestData['username']);
      }
      if (isset($requestData['email'])) {
        $user->setEmail($requestData['email']);
      }
      if (isset($requestData['password'])) {
        $user->setPassword($requestData['password']);
        // dd($user);
      }
      $this->em->persist($user);
      $this->em->flush();
      $userJson = $this->serializeUser($user);
      return new JsonResponse($userJson);
    } else {
      return new Response('Invalid user data', Response::HTTP_BAD_REQUEST);
    }
  }

  /**
   * Update user.
   */
  #[Route('/delete-user/{id}', name: 'create_user')]
  public function delete(int $id): Response
  {
    $user = $this->em->getRepository(Users::class)->find($id);
    if ($user === NULL) {
      return new Response('User not found', Response::HTTP_NOT_FOUND);
    }
    $this->em->remove($user);
    $this->em->flush();
    return new Response('User deleted successfully', Response::HTTP_OK);
  }

  /**
   * Serializes a User entity to JSON format.
   */
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
