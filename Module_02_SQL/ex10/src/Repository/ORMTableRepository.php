<?php

namespace App\Repository;

use App\Entity\ORMTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Persistence\ManagerRegistry;

class ORMTableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ORMTable::class);
    }

    public function createSchema(): void
    {
        $em = $this->getEntityManager();
        $schemaTool = new SchemaTool($em);
        $metadata = $em->getClassMetadata(ORMTable::class);
        $schemaTool->updateSchema([$metadata]);
    }

    public function save(ORMTable $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function saveUsernames(array $usernames): void
    {
        $em = $this->getEntityManager();
        $seen = [];
        foreach ($usernames as $username) {
            $username = trim($username);
            if (empty($username) || isset($seen[$username])) {
                continue;
            }
            $seen[$username] = true;
            $existing = $this->findOneBy(['username' => $username]);
            if (!$existing) {
                $ormEntry = new ORMTable();
                $ormEntry->setUsername($username);
                $em->persist($ormEntry);
            }
        }
        $em->flush();
    }

    public function getAll(): array
    {
        return $this->findAll();
    }
}
