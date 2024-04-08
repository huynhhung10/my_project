<?php

namespace App\Controller;
use App\Entity\Genres;
use App\Form\GenresFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Genre class.
 */
class GenreController extends AbstractController
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
  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->em = $entityManager;
  }
  
  /**
   * Show all genres.
   */
  #[Route('/admin/genres', name: 'genres')]
  public function indexGenre(): Response
  {
      $genres = $this->em->getRepository(Genres::class)->findAll();
      return $this->render('genre/index.html.twig', [
        
        'genres' => $genres
      ]);
  }
    /**
   * Create a new genre.
   */
  #[Route('/admin/create-genre', name: 'create-genre')]
  public function createGenre(Request $request)
  {
    $genre= new Genres();
    $form = $this->createForm(GenresFormType::class, $genre);
    // $form->handleRequest($request);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

      $this->em->persist($genre);
      $this->em->flush();
      $this->addFlash('insert_genre', 'true');
      return $this->redirectToRoute('genres');
    }

    return $this->render('genre/genre.html.twig', [
      'form' => $form->createView(),
      
    ]);
  }

  /**
   * Edit a Genre.
   */
  #[Route('/admin/edit-genre/{id}', name: 'edit-genre')]
  public function editGenre(Request $request, int $id): Response
  {
    
    $genre = $this->em->getRepository(Genres::class)->find($id);

    if (!$genre) {
      return new Response('Genre not found', Response::HTTP_NOT_FOUND);
    }

    $form = $this->createForm(GenresFormType::class, $genre);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->em->persist($genre);
      $this->em->flush();
      $this->addFlash('update_genre', 'true');
      return $this->redirectToRoute('genres');
    }
    return $this->render('genre/genre.html.twig', [
      'form' => $form->createView(),
    ]);
  }

/**
 * Delete a genre.
 */
#[Route('/admin/delete-genre/{id}', name: 'delete_genre')]
public function deleteGenre(Request $request,int $id): Response
{
    $genre = $this->em->getRepository(Genres::class)->find($id);

    if (!$genre) {
        return new Response('Genre not found', Response::HTTP_NOT_FOUND);
    }

    $moviesUsingGenre = $this->em->getRepository(Movies::class)->findBy(['Genre' => $genre]);

    if (!empty($moviesUsingGenre)) {
      
      $this->addFlash('error', 'Không thể xóa thể loại vì đang được sử dụng bởi một số bộ phim.');
        return $this->redirectToRoute('genres'); 
    }
    $this->em->remove($genre);
    $this->em->flush();
    $this->addFlash('delete_genre', 'true');
    return $this->redirectToRoute('genres'); 
}

  /**
   * Search for genre.
   */
  #[Route('/admin/search-genre', name: 'search-genre')]
  public function searchGenre(Request $request): Response
  {
    $searchQuery = $request->query->get('search_query');
    
    $queryBuilder = $this->em->createQueryBuilder();
    $queryBuilder
    ->select('g') 
    ->from('App\Entity\Genres', 'g');
 

    $queryBuilder
        ->andWhere("g.name LIKE :searchQuery")
        ->setParameter('searchQuery', '%'.$searchQuery.'%');


    $Genres = $queryBuilder->getQuery()->getResult();
    $formattedGenres = [];
    foreach($Genres as $genre) {
      $formattedGenres[] = [
        'id' => $genre->getId(),
        'name' => $genre->getName()

    ];
    }
    return $this->json($formattedGenres);
  }
}
