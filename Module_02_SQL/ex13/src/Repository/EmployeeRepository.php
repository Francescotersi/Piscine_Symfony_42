<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\QueryBuilder;

class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function save(Employee $employee, bool $flush = true): void
    {
        $this->getEntityManager()->persist($employee);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Employee $employee, bool $flush = true): void
    {
        $this->getEntityManager()->remove($employee);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function createOrResetSchema(): void
    {
        $em = $this->getEntityManager();
        $schemaTool = new SchemaTool($em);
        $metadata = [$em->getClassMetadata(Employee::class)];
        $schemaTool->updateSchema($metadata);
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    public function findPotentialManagersQueryBuilder(?int $excludeId = null): QueryBuilder
    {
        $qb = $this->createQueryBuilder('e');
        $qb->where($qb->expr()->in('e.position', ['manager', 'account_manager', 'qa_manager', 'dev_manager', 'ceo', 'coo']));
        if ($excludeId !== null) {
            $qb->andWhere('e.id != :myId')
               ->setParameter('myId', $excludeId);
        }
        return $qb;
    }
}
