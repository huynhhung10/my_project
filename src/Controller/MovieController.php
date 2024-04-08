<?php

namespace App\Controller;

use App\Entity\Genres;
use App\Entity\Movies;
use App\Form\MoviesFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Movie class.
 */
class MovieController extends AbstractController{
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
  * Show Movies.
  */
  #[Route('admin/movies', name: 'movies')]
  public function index(): Response {
    $movies = $this->em->getRepository(Movies::class)->findAll();
    return $this->render('movie/index.html.twig', [
      'movies' => $movies,
    ]);
  }

  /**
  * Create customer.
  */
  #[Route('/admin/create-movie', name: 'create-movie')]
  public function createMovie(Request $request) {
    $movie = new Movies();
    $form = $this->createForm(MoviesFormType::class, $movie);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->em->persist($movie);
      $this->em->flush();

      $this->addFlash('insert_movie', 'true');
      return $this->redirectToRoute('movies');
    }
    return $this->render('movie/movie.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  /**
  * Edit customer.
  */
  #[Route('/admin/edit-movie/{id}', name: 'edit-movie')]
  public function editMovie(Request $request, $id) {
    $movie = $this->em->getRepository(Movies::class)->find($id);
    $form = $this->createForm(MoviesFormType::class, $movie);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->em->persist($movie);
      $this->em->flush();

      $this->addFlash('update_movie', 'true');
      return $this->redirectToRoute('movies');
    }
    return $this->render('movie/movie.html.twig', [
      'form' => $form->createView(),
    ]);
  }
  /**
  * Delete a movie.
  */
  #[Route('/admin/delete-movie/{id}', name: 'delete-movie')]
  public function deleteMovie(Request $request, $id) {
    $movie = $this->em->getRepository(Movies::class)->find($id);
    if ($movie) {
      $this->em->remove($movie);
      $this->em->flush();

      $this->addFlash('delete_movie', 'true');
      return $this->redirectToRoute('movies');
    }
    return new Response('Invalid movie data', Response::HTTP_BAD_REQUEST);
  }
  /**
   * Search for movies.
   */
  #[Route('/admin/search-movie', name: 'search-movie')]
  public function searchMovie(Request $request): Response
  {
    $searchQuery = $request->query->get('search_query');
    $searchField = $request->query->get('search_field');
    $queryBuilder = $this->em->createQueryBuilder();
    $queryBuilder
        ->select('m', 'g') 
        ->from('App\Entity\Movies', 'm')
        ->leftJoin('m.Genre', 'g');

    if ($searchField === 'name') {
        $queryBuilder
            ->where("g.$searchField LIKE :searchQuery")
            ->setParameter('searchQuery', '%'.$searchQuery.'%');
    } elseif ($searchField === 'name') {
        $queryBuilder
            ->where("m.$searchField LIKE :searchQuery")
            ->setParameter('searchQuery', '%'.$searchQuery.'%');
    }
    $movies = $queryBuilder->getQuery()->getResult();
    $formattedMovies = [];
    foreach($movies as $movie) {
      $formattedMovies[] = [
        'id' => $movie->getId(),
        'title' => $movie->getTitle(),
        'genre' => [
            'id' => $movie->getGenre()->getId(),
            'name' => $movie->getGenre()->getName()
        ]
    ];
    }
    return $this->json($formattedMovies);
  }
}
