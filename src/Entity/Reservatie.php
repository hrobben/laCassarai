<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservatieRepository")
 */
class Reservatie
{
    public function __construct()
    {
        $this->tafel = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reservaties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tafel")
     */
    private $tafel;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $datumTijd;

    /**
     * @ORM\Column(type="integer")
     */
    private $aantal;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reservaties")
     */
    private $medewerker;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Tafel[]
     */
    public function getTafel(): Collection
    {
        return $this->tafel;
    }

    public function addTafel(Tafel $tafel): self
    {
        if (!$this->tafel->contains($tafel)) {
            $this->tafel[] = $tafel;
        }

        return $this;
    }

    public function removeTafel(Tafel $tafel): self
    {
        if ($this->tafel->contains($tafel)) {
            $this->tafel->removeElement($tafel);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDatumTijd(): ?\DateTimeInterface
    {
        return $this->datumTijd;
    }

    public function setDatumTijd(\DateTimeInterface $datumTijd): self
    {
        $this->datumTijd = $datumTijd;

        return $this;
    }

    public function getAantal(): ?int
    {
        return $this->aantal;
    }

    public function setAantal(int $aantal): self
    {
        $this->aantal = $aantal;

        return $this;
    }

    public function getMedewerker(): ?User
    {
        return $this->medewerker;
    }

    public function setMedewerker(?User $medewerker): self
    {
        $this->medewerker = $medewerker;

        return $this;
    }
}
