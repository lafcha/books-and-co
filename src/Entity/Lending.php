<?php

namespace App\Entity;

use App\Repository\LendingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LendingRepository::class)
 */
class Lending
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="makes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $borrower;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="lending")
     */
    private $linkedWith;

    /**
     * @ORM\OneToOne(targetEntity=UsersBook::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $usersBook;


    public function __construct()
    {
        $this->linkedWith = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getBorrower(): ?User
    {
        return $this->borrower;
    }

    public function setBorrower(?User $borrower): self
    {
        $this->borrower = $borrower;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getLinkedWith(): Collection
    {
        return $this->linkedWith;
    }

    public function addLinkedWith(Message $linkedWith): self
    {
        if (!$this->linkedWith->contains($linkedWith)) {
            $this->linkedWith[] = $linkedWith;
            $linkedWith->setLending($this);
        }

        return $this;
    }

    public function removeLinkedWith(Message $linkedWith): self
    {
        if ($this->linkedWith->removeElement($linkedWith)) {
            // set the owning side to null (unless already changed)
            if ($linkedWith->getLending() === $this) {
                $linkedWith->setLending(null);
            }
        }

        return $this;
    }

    public function getUsersBook(): ?UsersBook
    {
        return $this->usersBook;
    }

    public function setUsersBook(UsersBook $usersBook): self
    {
        $this->usersBook = $usersBook;

        return $this;
    }
}
