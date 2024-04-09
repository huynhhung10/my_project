<?php

namespace App\Entity;
use App\Entity\Customers;
use App\Entity\Movies;
use App\Repository\ReviewsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewsRepository::class)]
class Reviews
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    private ?string $reviewtext = null;
    #[ORM\Column]
    private ?int $rating = null;
  
    #[ORM\ManyToOne(inversedBy: 'movies')]
    private ?Movies $movie = null;
    
    #[ORM\ManyToOne(inversedBy: 'customers')]
    private ?Customers $customer = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getReviewText(): ?string
    {
        return $this->reviewtext;
    }

    public function setReviewText(string $reviewtext): static
    {
        $this->reviewtext = $reviewtext;

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

    public function getMovie(): ?Movies
    {
        return $this->movie;
    }

    public function setMovie(?Movies $movie): static
    {
        $this->movie = $movie;

        return $this;
    }
    public function getCustomer(): ?Customers
    {
        return $this->customer;
    }

    public function setCustomer(?Customers $customer): static
    {
        $this->customer = $customer;

        return $this;
    }
}
