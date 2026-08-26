<?php

namespace App\Controller;

use App\Service\databaseHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\personEntity;
use App\Entity\bankAccountEntity;
use App\Entity\addressEntity;
use Doctrine\ORM\EntityManagerInterface;


class ex09Controller extends AbstractController {

    #[Route(path:"/new", name:"ex09_newTable")]
    public function newTable(databaseHandler $dbHandler): Response {
        $message = $dbHandler->newTable();
        return new Response($message);
    }

    #[Route(path:"/delete", name:"ex09_deleteTable")]
    public function deleteTable(databaseHandler $dbHandler): Response {
        $message = $dbHandler->deleteTable();
        return new Response($message);
    }

    #[Route(path:"/person/create", name:"ex09_createPerson")]
    public function createPerson(EntityManagerInterface $em): Response {
        $person = new personEntity();
        $uniq = uniqid();
        $person->setUsername("user_" . $uniq);
        $person->setName("Name " . $uniq);
        $person->setEmail("email_" . $uniq . "@test.com");
        $person->setEnable("1");
        $person->setBirthdate("1990-01-01");

        $em->persist($person);
        $em->flush();

        return new Response("Created PersonEntity with ID: " . $person->getId());
    }

    #[Route(path:"/person/{id}/add-bank-account", name:"ex09_addBankAccount")]
    public function addBankAccount(int $id, EntityManagerInterface $em): Response {
        $person = $em->getRepository(personEntity::class)->find($id);
        if (!$person) {
            return new Response("Person not found", 404);
        }
        $account = new bankAccountEntity();
        $account->setBalance(rand(100, 5000));
        $person->setBankAccount($account);

        $em->persist($person);
        $em->flush();

        return new Response("Created Bank Account ID: " . $account->getId() . " and assigned to Person ID: " . $person->getId());
    }

    #[Route(path:"/person/{id}/add-address", name:"ex09_addAddress")]
    public function addAddress(int $id, EntityManagerInterface $em): Response {
        $person = $em->getRepository(personEntity::class)->find($id);
        if (!$person) {
            return new Response("Person not found", 404);
        }
        $address = new addressEntity();
        $address->setAddress("Main Street " . rand(1, 100));
        $person->addAddress($address);

        $em->persist($person);
        $em->flush();

        return new Response("Created Address ID: " . $address->getId() . " and assigned to Person ID: " . $person->getId());
    }

    #[Route(path:"/list", name:"ex09_list")]
    public function list(databaseHandler $dbHandler): Response {
        $persons = $dbHandler->fetchAll(personEntity::class);

        return $this->render('list.html.twig', [
            'persons' => $persons
        ]);
    }
}