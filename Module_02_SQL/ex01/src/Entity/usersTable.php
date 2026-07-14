<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class usersTable {

    #[ORM\Id]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type:"integer")]
    private ?int $id;

    #[ORM\Column(type:"string", length:255, unique: true)]
    private ?string $username;

    #[ORM\Column(type:"string", length:255)]
    private ?string $name;

    #[ORM\Column(type:"string", length:255, unique: true)]
    private ?string $email;

    #[ORM\Column(type:"boolean")]
    private ?string $enable;

    #[ORM\Column(type:"date_immutable")]
    private ?string $birthdate;

    #[ORM\Column(type:"text", length: 4294967295, nullable: true)]
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

    public function getBirthdate() {
        return $this->birthdate;
    }

    public function setBirthdate(string $birthdate) {
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