<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\ORMTableRepository;

#[ORM\Entity(repositoryClass: ORMTableRepository::class)]
#[ORM\Table(name: 'ORM_table')]
class ORMTable {

    #[ORM\Id]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type:"integer")]
    private ?int $id;

    #[ORM\Column(type:"string", length:255, unique: true)]
    #[Assert\NotBlank(message: 'The username cannot be empty.')]
    private ?string $username;

    public function getId(): ?int {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername(string $username) {
        $this->username = $username;
        return $this;
    }
}