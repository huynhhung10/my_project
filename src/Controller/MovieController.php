<?php

namespace App\Controller;

use DateTime;
use App\Entity\Genres;
use App\Entity\Movies;
use App\Form\MoviesFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Movie class.
 */
class MovieController extends AbstractController
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
   * Show Movies.
   */
  #[Route('admin/movies', name: 'movies')]
  public function index(Request $request, PaginatorInterface $paginator): Response
  {
    $movies = $this->em->getRepository(Movies::class)->findAll();
    $pagination = $paginator->paginate(
      $movies,
      $request->query->getInt('page', 1),
      $request->query->getInt('limit', 5)
    );
    return $this->render('movie/index.html.twig', [
      'movies' =>  $pagination,
    ]);
  }

  /**
   * Create customer.
   */
  #[Route('/admin/create-movie', name: 'create-movie')]
  public function createMovie(Request $request)
  {
    $movie = new Movies();
    $form = $this->createForm(MoviesFormType::class, $movie);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $title = $form->get('title')->getData();
      $existingTitle = $this->em->getRepository(Movies::class)->findOneBy(['title' => $title]);

      if ($existingTitle) {
        $this->addFlash('error_title', 'Tiêu đề phim đã tồn tại.');
        return $this->redirectToRoute('create-movie');
      }
      $movie->setCreateat(new DateTime());
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
  public function editMovie(Request $request, $id)
  {
    $movie = $this->em->getRepository(Movies::class)->find($id);
    $form = $this->createForm(MoviesFormType::class, $movie);
    $form->handleRequest($request);
    $title = $form->get('title')->getData();
    $existingTitle = $this->em->getRepository(Movies::class)->findOneBy(['title' => $title]);
    if ($form->isSubmitted() && $form->isValid()) {
      if ($existingTitle) {
        if ($existingTitle->getId() != $id) {
          $this->addFlash('error_title', 'Tiêu đề phim đã tồn tại.');
          return $this->redirectToRoute('edit-movie', ['id' => $id]);
        } 
      }
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
  public function deleteMovie( $id)
  {
    $movie = $this->em->getRepository(Movies::class)->find($id);
    $queryBuilder = $this->em->createQueryBuilder();
    $queryBuilder
      ->select('m')
      ->from('App\Entity\Reviews', 'm')
      ->leftJoin('m.movie', 'g')
      ->where("g.id= $id");
    $review = $queryBuilder->getQuery()->getResult();
    if ($movie || $review) {
      foreach ($review as $r) {
        $this->em->remove($r);
      }
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
        ->setParameter('searchQuery', '%' . $searchQuery . '%');
    } elseif ($searchField === 'title') {
      $queryBuilder
        ->where("m.$searchField LIKE :searchQuery")
        ->setParameter('searchQuery', '%' . $searchQuery . '%');
    }
    $movies = $queryBuilder->getQuery()->getResult();
    $formattedMovies = [];
    foreach ($movies as $movie) {
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
