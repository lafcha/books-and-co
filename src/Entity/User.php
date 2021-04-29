<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $avatar;

    /**
     * @ORM\Column(type="integer")
     */
    private $county;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $city;

    /**
     * @ORM\ManyToMany(targetEntity=UsersBook::class, inversedBy="user")
     */
    private $userHasBook;

    /**
     * @ORM\OneToMany(targetEntity=Lending::class, mappedBy="borrower")
     */
    private $makes;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="sender")
     */
    private $sends;

    public function __construct()
    {
        $this->userHasBook = new ArrayCollection();
        $this->makes = new ArrayCollection();
        $this->sends = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getCounty(): ?int
    {
        return $this->county;
    }

    public function setCounty(int $county): self
    {
        $this->county = $county;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|UsersBook[]
     */
    public function getUserHasBook(): Collection
    {
        return $this->userHasBook;
    }

    public function addUserHasBook(UsersBook $userHasBook): self
    {
        if (!$this->userHasBook->contains($userHasBook)) {
            $this->userHasBook[] = $userHasBook;
        }

        return $this;
    }

    public function removeUserHasBook(UsersBook $userHasBook): self
    {
        $this->userHasBook->removeElement($userHasBook);

        return $this;
    }

    /**
     * @return Collection|Lending[]
     */
    public function getMakes(): Collection
    {
        return $this->makes;
    }

    public function addMake(Lending $make): self
    {
        if (!$this->makes->contains($make)) {
            $this->makes[] = $make;
            $make->setBorrower($this);
        }

        return $this;
    }

    public function removeMake(Lending $make): self
    {
        if ($this->makes->removeElement($make)) {
            // set the owning side to null (unless already changed)
            if ($make->getBorrower() === $this) {
                $make->setBorrower(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getSends(): Collection
    {
        return $this->sends;
    }

    public function addSend(Message $send): self
    {
        if (!$this->sends->contains($send)) {
            $this->sends[] = $send;
            $send->setSender($this);
        }

        return $this;
    }

    public function removeSend(Message $send): self
    {
        if ($this->sends->removeElement($send)) {
            // set the owning side to null (unless already changed)
            if ($send->getSender() === $this) {
                $send->setSender(null);
            }
        }

        return $this;
    }
}
