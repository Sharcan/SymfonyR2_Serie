<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SerieRepository")
 */
class Serie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="date")
     */
    private $startedAt;

    /**
     * @ORM\Column(type="date")
     */
    private $EndedAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\File(mimeTypes={"image/png", "image/jpeg"})
     */
    private $image;

    /**
     * @ORM\Column(type="integer")
     */
    private $number_season;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="series")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeInterface $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeInterface
    {
        return $this->EndedAt;
    }

    public function setEndedAt(\DateTimeInterface $EndedAt): self
    {
        $this->EndedAt = $EndedAt;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getNumberSeason(): ?int
    {
        return $this->number_season;
    }

    public function setNumberSeason(int $number_season): self
    {
        $this->number_season = $number_season;

        return $this;
    }

    public function getCategorieId(): ?Categorie
    {
        return $this->categorie_id;
    }

    public function setCategorieId(?Categorie $categorie_id): self
    {
        $this->categorie_id = $categorie_id;

        return $this;
    }
}
