<?php

namespace App\Controller;

use App\Entity\addressEntity;
use App\Entity\bankAccountEntity;
use App\Entity\personEntity;
use App\Repository\personEntityRepository;
use App\Service\databaseHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ex09Controller extends AbstractController
{
    #[Route(path: "/new", name: "ex09_newTable")]
    public function newTable(databaseHandler $dbHandler): Response
    {
        $message = $dbHandler->newTable();
        $this->addFlash('success', $message);
        return $this->redirectToRoute('ex09_list');
    }

    #[Route(path: "/delete", name: "ex09_deleteTable")]
    public function deleteTable(databaseHandler $dbHandler): Response
    {
        $message = $dbHandler->deleteTable();
        $this->addFlash('success', $message);
        return $this->redirectToRoute('ex09_list');
    }

    #[Route(path: "/person/create", name: "ex09_createPerson")]
    public function createPerson(personEntityRepository $personRepository): Response
    {
        $person = new personEntity();
        $uniq = uniqid();
        $person->setUsername("user_" . $uniq);
        $person->setName("Name " . $uniq);
        $person->setEmail("email_" . $uniq . "@test.com");
        $person->setEnable(true);
        $person->setBirthdate(new \DateTime('1990-01-01 00:00:00'));

        $personRepository->save($person);

        $this->addFlash('success', 'Created PersonEntity with ID: ' . $person->getId());
        return $this->redirectToRoute('ex09_list');
    }

    #[Route(path: "/person/{id}/add-bank-account", name: "ex09_addBankAccount")]
    public function addBankAccount(int $id, personEntityRepository $personRepository): Response
    {
        $person = $personRepository->find($id);
        if (!$person) {
            $this->addFlash('error', 'Person not found');
            return $this->redirectToRoute('ex09_list');
        }
        $account = new bankAccountEntity();
        $account->setBalance(rand(100, 5000));
        $person->setBankAccount($account);

        $personRepository->save($person);

        $this->addFlash('success', 'Created Bank Account ID: ' . $account->getId() . ' and assigned to Person ID: ' . $person->getId());
        return $this->redirectToRoute('ex09_list');
    }

    #[Route(path: "/person/{id}/add-address", name: "ex09_addAddress")]
    public function addAddress(int $id, personEntityRepository $personRepository): Response
    {
        $person = $personRepository->find($id);
        if (!$person) {
            $this->addFlash('error', 'Person not found');
            return $this->redirectToRoute('ex09_list');
        }
        $address = new addressEntity();
        $address->setAddress("Main Street " . rand(1, 100));
        $person->addAddress($address);

        $personRepository->save($person);

        $this->addFlash('success', 'Created Address ID: ' . $address->getId() . ' and assigned to Person ID: ' . $person->getId());
        return $this->redirectToRoute('ex09_list');
    }

    #[Route(path: "/list", name: "ex09_list")]
    public function list(personEntityRepository $personRepository): Response
    {
        $persons = $personRepository->getAll();

        return $this->render('list.html.twig', [
            'persons' => $persons,
        ]);
    }
}