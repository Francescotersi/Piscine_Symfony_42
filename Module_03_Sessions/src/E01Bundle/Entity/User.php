<?php

namespace App\E01Bundle\Entity;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

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
}