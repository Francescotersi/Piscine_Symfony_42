<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\SchemaTool;

use App\Entity\personEntity;
use App\Entity\bankAccountEntity;

class personRepository extends ServiceEntityRepository
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

    public function seed(array $users): void
    {
        $em = $this->getEntityManager();
        foreach ($users as $u) {
            $newUser = new personEntity();
            $newBankAccount = new bankAccountEntity();
            $newUser->setUsername($u[0]);
            $newUser->setName($u[1]);
            $newUser->setEmail($u[2]);
            $newBankAccount->setBalance($u[3]);
            $newUser->setBankAccount($newBankAccount);
            $em->persist($newUser);
        }
        $em->flush();
    }

    public function createTables(): void
    {
        $em = $this->getEntityManager();
        $schemaTool = new SchemaTool($em);
        $metadata = [
            $em->getClassMetadata(personEntity::class),
            $em->getClassMetadata(bankAccountEntity::class)
        ];
        $schemaTool->updateSchema($metadata);
    }

    public function findWithAccountFilteredAndSorted(
        ?string $name,
        ?int $minMoney,
        string $sortBy = 'person.name',
        string $order = 'ASC'
    ): array {
        $qb = $this->createQueryBuilder('person');
        $qb->leftJoin('person.bankAccount', 'bank')
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