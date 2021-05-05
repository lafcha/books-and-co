<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $cover;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $editor;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $year;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $slug;

    /**
     * @ORM\Column(type="bigint")
     */
    private $isbn;

    /**
     * @ORM\OneToMany(targetEntity=UsersBook::class, mappedBy="book")
     */
    private $bookBelongsToUser;

    public function __construct()
    {
        $this->bookBelongsToUser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getEditor(): ?string
    {
        return $this->editor;
    }

    public function setEditor(string $editor): self
    {
        $this->editor = $editor;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * @return Collection|UsersBook[]
     */
    public function getBookBelongsToUser(): Collection
    {
        return $this->bookBelongsToUser;
    }

    public function addBookBelongsToUser(UsersBook $bookBelongsToUser): self
    {
        if (!$this->bookBelongsToUser->contains($bookBelongsToUser)) {
            $this->bookBelongsToUser[] = $bookBelongsToUser;
            $bookBelongsToUser->setBook($this);
        }

        return $this;
    }

    public function removeBookBelongsToUser(UsersBook $bookBelongsToUser): self
    {
        if ($this->bookBelongsToUser->removeElement($bookBelongsToUser)) {
            // set the owning side to null (unless already changed)
            if ($bookBelongsToUser->getBook() === $this) {
                $bookBelongsToUser->setBook(null);
            }
        }

        return $this;
    }
}
