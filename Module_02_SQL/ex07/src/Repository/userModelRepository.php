<?php

namespace App\Repository;

use App\Entity\userModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class userModelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, userModel::class);
    }

    public function save(userModel $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(userModel $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function removeById(int $id): bool
    {
        $entity = $this->find($id);
        if (!$entity) {
            return false;
        }
        $this->remove($entity);
        return true;
    }

    public function getById(int $id): ?userModel
    {
        return $this->find($id);
    }

    public function getAll(): array
    {
        return $this->findAll();
    }
}
