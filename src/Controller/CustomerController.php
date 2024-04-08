<?php

namespace App\Controller;

use App\Entity\Customers;
use App\Form\CustomersFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Customer Controller.
 */
class CustomerController extends AbstractController
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
   * Show customers.
   */
  #[Route('/admin/customers', name: 'customers')]
  public function index(): Response
  {
    $customers = $this->em->getRepository(Customers::class)->findAll();
    return $this->render('customer/index.html.twig', [
      'customers' => $customers,
    ]);
  }

  /**
   * Create customer.
   */
  #[Route('/admin/create-customer', name: 'create-customer')]
  public function createCustomer(Request $request)
  {
    $customer = new Customers();
    $form = $this->createForm(CustomersFormType::class, $customer);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->em->persist($customer);
      $this->em->flush();

      $this->addFlash('insert_cus', 'true');
      return $this->redirectToRoute('customers');
    }
    return $this->render('customer/customer.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  /**
   * Edit customer.
   */
  #[Route('/admin/edit-customer/{id}', name: 'edit-customer')]
  public function editCustomer(Request $request, $id)
  {

    $customer = $this->em->getRepository(Customers::class)->find($id);
    $form = $this->createForm(CustomersFormType::class, $customer);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->em->persist($customer);
      $this->em->flush();

      $this->addFlash('update_cus', 'true');
      return $this->redirectToRoute('customers');
    }
    return $this->render('customer/customer.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  /**
   * Delete a customer.
   */
  #[Route('/admin/delete-customer/{id}', name: 'delete-customer')]
  public function deleteCustomer(Request $request, $id)
  {
    $customer = $this->em->getRepository(Customers::class)->find($id);
    if ($customer) {
      $this->em->remove($customer);
      $this->em->flush();

      $this->addFlash('delete_cus', 'true');
      return $this->redirectToRoute('customers');
    }
    return new Response('Invalid customer data', Response::HTTP_BAD_REQUEST);
  }
  /**
   * Search for customers.
   */
  #[Route('/admin/search-customer', name: 'search-customer')]
  public function searchCustomer(Request $request): Response
  {
    $searchQuery = $request->query->get('search_query');
    $searchField = $request->query->get('search_field');
    $queryBuilder = $this->em->createQueryBuilder();
    $queryBuilder
      ->select('c')
      ->from('App\Entity\Customers', 'c')
      ->where("c.$searchField LIKE :searchQuery")
      ->setParameter('searchQuery', '%' . $searchQuery . '%');
    $customers = $queryBuilder->getQuery()->getResult();
    return $this->json($customers);
  }
}
