<?php

namespace App\Entity;

use App\Repository\UsersBookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsersBookRepository::class)
 */
class UsersBook
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAvailable  = 1;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userHasBook")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="bookBelongsToUser")
     * @ORM\JoinColumn(nullable=false)
     */
    private $book;

    /**
     * @ORM\OneToMany(targetEntity=Lending::class, mappedBy="usersBook", cascade={"remove"})
     */
    private $usersBookLinkedTo;

    public function __construct()
    {
        $this->usersBookLinkedTo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): self
    {
        $this->isAvailable = $isAvailable;

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

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    /**
     * @return Collection|Lending[]
     */
    public function getUsersBookLinkedTo(): Collection
    {
        return $this->usersBookLinkedTo;
    }

    public function addUsersBookLinkedTo(Lending $usersBookLinkedTo): self
    {
        if (!$this->usersBookLinkedTo->contains($usersBookLinkedTo)) {
            $this->usersBookLinkedTo[] = $usersBookLinkedTo;
            $usersBookLinkedTo->setUsersBook($this);
        }

        return $this;
    }

    public function removeUsersBookLinkedTo(Lending $usersBookLinkedTo): self
    {
        if ($this->usersBookLinkedTo->removeElement($usersBookLinkedTo)) {
            // set the owning side to null (unless already changed)
            if ($usersBookLinkedTo->getUsersBook() === $this) {
                $usersBookLinkedTo->setUsersBook(null);
            }
        }

        return $this;
    }
}
