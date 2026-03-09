<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;


    /**
     * @var Collection<int, Transactions>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Transactions::class, orphanRemoval: true)]
    private Collection $transactions;

    #[ORM\Column(length: 255)]
    private ?string $imgUrl = null;

    /**
     * @var Collection<int, Catagories>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Catagories::class)]
    private Collection $catagories;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->catagories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Transactions>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transactions $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setUser($this);
        }

        return $this;
    }

    public function removeTransaction(Transactions $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getUser() === $this) {
                $transaction->setUser(null);
            }
        }

        return $this;
    }

    public function getImgUrl(): ?string
    {
        return $this->imgUrl;
    }

    public function setImgUrl(string $imgUrl): static
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    /**
     * @return Collection<int, Catagories>
     */
    public function getCatagories(): Collection
    {
        return $this->catagories;
    }

    public function addCatagory(Catagories $catagory): static
    {
        if (!$this->catagories->contains($catagory)) {
            $this->catagories->add($catagory);
            $catagory->setUser($this);
        }

        return $this;
    }

    public function removeCatagory(Catagories $catagory): static
    {
        if ($this->catagories->removeElement($catagory)) {
            // set the owning side to null (unless already changed)
            if ($catagory->getUser() === $this) {
                $catagory->setUser(null);
            }
        }

        return $this;
    }
}
