<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name:'BankAccount')]
class bankAccountEntity {
    #[ORM\Id]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type:"integer")]
    private ?int $id;

    #[ORM\OneToOne(targetEntity: personEntity::class, mappedBy: 'bankAccount')]
    private ?personEntity $owner = null;    

    #[ORM\Column(type:"integer")]
    private ?int $balance;

// -----------------------------------------------------

    public function getId(): ?int {
        return $this->id;
    }
    public function getBalance(): ?int {
        return $this->balance;
    }
    public function setBalance(int $balance): self {
        $this->balance = $balance;
        return $this;
    }
    public function getOwner(): ?personEntity {
        return $this->owner;
    }
    public function setOwner(?personEntity $owner): self {
        $this->owner = $owner;
        return $this;
    }
}