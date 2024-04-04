<?php
namespace App\Entity;

use App\Repository\ReviewsRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Users;
use App\Entity\Movies;
#[ORM\Entity(repositoryClass: ReviewsRepository::class)]
class Reviews
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    

    #[ORM\ManyToOne(targetEntity: Movies::class)]
    #[ORM\JoinColumn(name: "movie_id", referencedColumnName: "id")]
    private ?int $movie;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id")]
    private ?int $user;

    
    #[ORM\Column(length: 255)]
    private ?string $review_text;

    #[ORM\Column]
    private ?int $rating= null;

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMovie(): ?Movies
    {
        return $this->movie;
    }

    public function setMovie(?Movies $movie): static
    {
        $this->movie = $movie;
        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        $this->user = $user;
        return $this;
    }

   
    public function getReviewText(): ?int
    {
        return $this->review_text;
    }

    public function setReviewText(string $review_text): static
    {
        $this->$review_text = $review_text;
        return $this;
    }


    public function getRating(): ?int
    {
        return $this->rating;
    }


    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }
    
}
