<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\personEntity;

/**
 * @extends ServiceEntityRepository<personEntity>
 */
class personRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, personEntity::class);
    }

    public function findWithAccountFilteredAndSorted(
        ?string $name,
        ?int $minMoney,
        string $sortBy = 'person.name',
        string $order = 'ASC'
    ): array {
        $qb = $this->createQueryBuilder('person');
        $qb->innerJoin('person.bankAccount', 'bank')
           ->addSelect('bank');

        if (!empty($name)) {
            $qb->andWhere('person.name LIKE :name')
               ->setParameter('name', '%' . $name . '%');
        }

        if ($minMoney !== null) {
            $qb->andWhere('bank.balance >= :minMoney')
               ->setParameter('minMoney', $minMoney);
        }

        $qb->orderBy($sortBy, $order);

        return $qb->getQuery()->getResult();
    }

}