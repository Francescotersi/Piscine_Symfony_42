<?php

namespace App\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
#[UniqueEntity(fields: ['username', 'email'], message: 'This fields is alredy in use choose another one')]
class userModel {

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

    #[ORM\Column(type:"text", length: 4294967295, nullable: true)]
    #[Assert\NotBlank(message: 'The address cannot be empty.')]
    private ?string $address;

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

    public function getAddress() {
        return $this->address;
    }

    public function setAddress(?string $address) {
        $this->address = $address;
        return $this;
    }
}