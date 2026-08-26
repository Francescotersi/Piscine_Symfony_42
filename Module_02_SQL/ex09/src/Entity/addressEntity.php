<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name:'addresses')]
class addressEntity {
    #[ORM\Id]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type:"integer")]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: personEntity::class, inversedBy: 'addresses')]
    #[ORM\JoinColumn(name: 'person_id', referencedColumnName: 'id', nullable: false)]
    private ?personEntity $person = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $address = null;

// ---------------------------------------------------------

    public function getId(): ?int {
        return $this->id;
    }

    public function getAddress(): ?string {
        return $this->address;
    }

    public function setAddress(string $address): self {
        $this->address = $address;
        return $this;
    }

    public function getPerson(): ?personEntity {
        return $this->person;
    }

    public function setPerson(?personEntity $person): self {
        $this->person = $person;
        return $this;
    }
}