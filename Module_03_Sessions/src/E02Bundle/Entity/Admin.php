<?php

namespace App\E02Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\E03Bundle\Entity\Post;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'admins')]
class Admin implements UserInterface, PasswordAuthenticatedUserInterface {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "The Username cannot be empty")]
    private ?string $username = null;

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private array $roles = ['ROLE_ADMIN'];

    #[ORM\OneToMany(mappedBy: 'authorAdmin', targetEntity: Post::class)]
    private Collection $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_ADMIN';
        $roles[] = 'ROLE_USER';
        return array_values(array_unique($roles));
    }

    public function getUserIdentifier(): string{
        return (string)$this->username;
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