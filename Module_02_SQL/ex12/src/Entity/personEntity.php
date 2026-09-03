<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

use App\Repository\personRepository;

#[ORM\Entity(repositoryClass: personRepository::class)]
#[ORM\Table(name: 'persons')]
class personEntity {
    
    #[ORM\Id]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type:"integer")]
    private ?int $id;

    #[ORM\Column(type:"string", length:255, unique: true)]
    #[Assert\NotBlank(message: 'The username cannot be empty.')]
    private ?string $username;

    #[ORM\Column(type:"string", length:255)]
    #[Assert\NotBlank(message: 'The name cannot be empty.')]
    private ?string $name;

    #[ORM\Column(type:"string", length:255, unique: true)]
    #[Assert\NotBlank(message: 'The email cannot be empty.')]
    private ?string $email;

    #[ORM\OneToOne(targetEntity: bankAccountEntity::class, inversedBy: 'owner', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'bank_account_id', referencedColumnName: 'id', nullable: true)]
    private ?bankAccountEntity $bankAccount = null;


// ----------------------------------------------------------

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

    public function getName() {
        return $this->name;
    }

    public function setName(string $name) {
        $this->name = $name;
        return $this;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail(string $email) {
        $this->email = $email;
        return $this;
    }

    public function getBankAccount(): ?bankAccountEntity {
        return $this->bankAccount;
    }

    public function setBankAccount(?bankAccountEntity $bankAccount): self {
        $this->bankAccount = $bankAccount;
        if ($bankAccount !== null && $bankAccount->getOwner() !== $this) {
            $bankAccount->setOwner($this);
        }
        return $this;
    }
}