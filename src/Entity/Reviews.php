<?php

namespace App\Entity;

use App\Repository\ReviewsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewsRepository::class)]
class Reviews
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    private ?Customers $customer = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    private ?Movies $movie = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $reviewtext = null;

    #[ORM\Column]
    private ?int $rating = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMovie(): ?Movies
    {
        return $this->movie;
    }

    public function setMovie(?Movies $movie): static
    {
        $this->movie = $movie;

        return $this;
    }

    public function getReviewtext(): ?string
    {
        return $this->reviewtext;
    }

    public function setReviewtext(string $reviewtext): static
    {
        $this->reviewtext = $reviewtext;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating ?? 0;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }
}
