<?php

namespace App\Controller;

use App\Entity\Customers;
use App\Form\CustomersFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class CustomerController extends AbstractController
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
  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

    #[Route('/customers', name: 'customers')]
    public function index(): Response
    {
        $customers = $this->em->getRepository(Customers::class)->findAll();
        return $this->render('customer/index.html.twig', [
            'customers' => $customers,
        ]);
    }
    
    #[Route('/admin/create-customer', name: 'create-customer')]
    public function createCustomer(Request $request) {
        $customer = new Customers();
        $form = $this->createForm(CustomersFormType::class, $customer);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($customer);
            $this->em->flush();

            $this->addFlash('insert', 'true');
            return $this->redirectToRoute('customers');
        }
        return $this->render('customer/customer.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/edit-customer/{id}', name: 'edit-customer')]
    public function editCustomer(Request $request, $id) {
        
        $customer = $this->em->getRepository(Customers::class)->find($id);
        $form = $this->createForm(CustomersFormType::class, $customer);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($customer);
            $this->em->flush();

            $this->addFlash('update', 'true');
            return $this->redirectToRoute('customers');
        }
        return $this->render('customer/customer.html.twig',[
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/admin/delete-customer/{id}', name: 'delete-customer')]
    public function deleteCustomer(Request $request, $id) {
        $customer = $this->em->getRepository(Customers::class)->find($id);
        if($customer) {
            $this->em->remove($customer);
            $this->em->flush();

            $this->addFlash('delete', 'true');
            return $this->redirectToRoute('customers');
        }
        return new Response('Invalid customer data', Response::HTTP_BAD_REQUEST);
    }
}
