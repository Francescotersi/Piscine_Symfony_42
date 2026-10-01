<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use App\Repository\EmployeeRepository;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[ORM\Table(name:'employees')]
#[UniqueEntity('email', message: 'This email is already in use by another employee.')]
class Employee {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column(type: "boolean")]
    private ?bool $active = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $employed_since = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $employed_until = null;

    #[ORM\Column(type: "integer")]
    #[Assert\Choice(choices: [8, 6, 4])]
    private ?int $hours = null;

    #[ORM\Column(type: "integer")]
    #[Assert\Range(max: 2147483647, maxMessage: "The salary is too high.")]
    private ?int $salary = null;

    #[ORM\Column(type: "string", length: 100)]
    #[Assert\Choice(choices: ['manager', 'account_manager', 'qa_manager', 'dev_manager', 'ceo', 'coo', 'backend_dev', 'frontend_dev', 'qa_tester'])]
    private ?string $position = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'subordinates')]
    #[ORM\JoinColumn(name: 'manager_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?self $manager = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'manager')]
    private Collection $subordinates;

    public function __construct()
    {
        $this->subordinates = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function getEmployedSince(): ?\DateTimeInterface
    {
        return $this->employed_since;
    }

    public function setEmployedSince(\DateTimeInterface $employed_since): self
    {
        $this->employed_since = $employed_since;
        return $this;
    }

    public function getEmployedUntil(): ?\DateTimeInterface
    {
        return $this->employed_until;
    }

    public function setEmployedUntil(?\DateTimeInterface $employed_until): self
    {
        $this->employed_until = $employed_until;
        return $this;
    }

    public function getHours(): ?int
    {
        return $this->hours;
    }

    public function setHours(int $hours): self
    {
        $this->hours = $hours;
        return $this;
    }

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(int $salary): self
    {
        $this->salary = $salary;
        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;
        return $this;
    }
    public function getManager(): ?self
    {
        return $this->manager;
    }

    public function setManager(?self $manager): self
    {
        $this->manager = $manager;
        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSubordinates(): Collection
    {
        return $this->subordinates;
    }

    public function addSubordinate(self $subordinate): self
    {
        if (!$this->subordinates->contains($subordinate)) {
            $this->subordinates->add($subordinate);
            $subordinate->setManager($this);
        }

        return $this;
    }

    public function removeSubordinate(self $subordinate): self
    {
        if ($this->subordinates->removeElement($subordinate)) {
            if ($subordinate->getManager() === $this) {
                $subordinate->setManager(null);
            }
        }

        return $this;
    }
}