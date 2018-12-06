<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TijdenRepository")
 */
class Tijden
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $dag;

    /**
     * @ORM\Column(type="time")
     */
    private $begintijd;

    /**
     * @ORM\Column(type="time")
     */
    private $eindtijd;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDag(): ?string
    {
        return $this->dag;
    }

    public function setDag(string $dag): self
    {
        $this->dag = $dag;

        return $this;
    }

    public function getBegintijd(): ?\DateTimeInterface
    {
        return $this->begintijd;
    }

    public function setBegintijd(\DateTimeInterface $begintijd): self
    {
        $this->begintijd = $begintijd;

        return $this;
    }

    public function getEindtijd(): ?\DateTimeInterface
    {
        return $this->eindtijd;
    }

    public function setEindtijd(\DateTimeInterface $eindtijd): self
    {
        $this->eindtijd = $eindtijd;

        return $this;
    }
}
