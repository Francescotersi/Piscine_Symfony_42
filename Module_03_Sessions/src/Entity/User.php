<?php

namespace App\Entity;

use App\E03Bundle\Entity\Post;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "The Username can`t be empty")]
    private ?string $username;

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: 'authorUser', targetEntity: 'App\E03Bundle\Entity\Post')]
    private Collection $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function setPosts(Collection $posts): static
    {
        $this->posts = $posts;
        return $this;
    }

    public function getId(): ?int {
        return $this->id;
    }
    public function getUsername(): ?string {
        return $this->username;
    }
    public function setUsername(string $username): static {
        $this->username = $username;
        return $this;
    }

    public function getUserIdentifier(): string {
        return (string)$this->username;
    }

    public function getRoles(): array {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles) {
        $this->roles = $roles;
        return $this;
    }

        public function getPassword(): ?string
    {
        return $this->password;
    }
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getReputation(): int
    {
        $reputation = 0;
        foreach ($this->posts as $post) {
            $reputation += $post->getLikesCount() - $post->getDislikesCount();
        }
        if ($reputation < 0) {
            $reputation = 0;
        }
        return $reputation;
    }
}