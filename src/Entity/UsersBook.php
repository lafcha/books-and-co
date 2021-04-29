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
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="userHasBook")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Book::class, mappedBy="bookHasUser")
     * @ORM\JoinColumn(name="book", referencedColumnName="isbn")
     */
    private $book;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_available;

    /**
     * @ORM\OneToOne(targetEntity=Lending::class, inversedBy="usersBook", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $usersBookIsLent;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->book = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->addUserHasBook($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->removeElement($user)) {
            $user->removeUserHasBook($this);
        }

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBook(): Collection
    {
        return $this->book;
    }

    public function addBook(Book $book): self
    {
        if (!$this->book->contains($book)) {
            $this->book[] = $book;
            $book->addBookHasUser($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->book->removeElement($book)) {
            $book->removeBookHasUser($this);
        }

        return $this;
    }

    public function getIsAvailable(): ?bool
    {
        return $this->is_available;
    }

    public function setIsAvailable(bool $is_available): self
    {
        $this->is_available = $is_available;

        return $this;
    }

    public function getUsersBookIsLent(): ?Lending
    {
        return $this->usersBookIsLent;
    }

    public function setUsersBookIsLent(Lending $usersBookIsLent): self
    {
        $this->usersBookIsLent = $usersBookIsLent;

        return $this;
    }
}
