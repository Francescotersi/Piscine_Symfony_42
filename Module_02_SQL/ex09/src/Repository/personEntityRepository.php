<?php

namespace App\Repository;

use App\Entity\personEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class personEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, personEntity::class);
    }

    public function save(personEntity $person, bool $flush = true): void
    {
        $this->getEntityManager()->persist($person);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(personEntity $person, bool $flush = true): void
    {
        $this->getEntityManager()->remove($person);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAll(): array
    {
        return $this->findAll();
    }
}
