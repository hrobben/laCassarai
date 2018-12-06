<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BankCartRepository")
 */
class BankCart
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bankCarts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $accountnr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bank;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cardnr;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserid(): ?User
    {
        return $this->userid;
    }

    public function setUserid(?User $userid): self
    {
        $this->userid = $userid;

        return $this;
    }

    public function getAccountnr(): ?string
    {
        return $this->accountnr;
    }

    public function setAccountnr(string $accountnr): self
    {
        $this->accountnr = $accountnr;

        return $this;
    }

    public function getBank(): ?string
    {
        return $this->bank;
    }

    public function setBank(?string $bank): self
    {
        $this->bank = $bank;

        return $this;
    }

    public function getCardnr(): ?string
    {
        return $this->cardnr;
    }

    public function setCardnr(?string $cardnr): self
    {
        $this->cardnr = $cardnr;

        return $this;
    }
}
