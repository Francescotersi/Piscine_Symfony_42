<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

// php bin/console make:migration
// php bin/console doctrine:migrations:migrate

#[ORM\Entity]
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

    #[ORM\Column(type:"boolean")]
    #[Assert\NotBlank(message: 'The enable feature cannot be empty.')]
    private ?string $enable;

    #[ORM\Column(type:"string", length:255)]
    #[Assert\NotBlank(message: 'The birthdate cannot be empty.')]
    private ?string $birthdate;

    // #[ORM\Column(type:"integer")]
    // private ?int $rings = 4;


    #[ORM\OneToOne(targetEntity: bankAccountEntity::class, inversedBy: 'owner', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'bank_account_id', referencedColumnName: 'id', nullable: true)]
    private ?bankAccountEntity $bankAccount = null;

    #[ORM\OneToMany(targetEntity: addressEntity::class, mappedBy: 'person', cascade: ['persist', 'remove'])]
    private Collection $addresses;

    public function __construct() {
        $this->addresses = new ArrayCollection();
    }

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

    public function isEnable() {
        return $this->enable;
    }

    public function setEnable(string $enable) {
        $this->enable = $enable;
        return $this;
    }

    public function getBirthdate(): ?string {
        return $this->birthdate;
    }

    public function setBirthdate(?string $birthdate) {
        $this->birthdate = $birthdate;
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

    public function getAddresses(): Collection {
        return $this->addresses;
    }

    public function addAddress(addressEntity $address): self {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
            $address->setPerson($this);
        }
        return $this;
    }

    public function removeAddress(addressEntity $address): self {
        if ($this->addresses->removeElement($address)) {
            if ($address->getPerson() === $this) {
                $address->setPerson(null);
            }
        }
        return $this;
    }
}