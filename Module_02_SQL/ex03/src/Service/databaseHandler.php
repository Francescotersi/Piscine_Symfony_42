<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class databaseHandler {

    private SchemaTool $schemaTool;
    private array $metadatas;

    public function __construct(private EntityManagerInterface $manager) {
        $this->metadatas = $manager->getMetadataFactory()->getAllMetadata();
        $this->schemaTool = new SchemaTool($manager);
    }

    public function newTable(): String {
        try {
            $this->schemaTool->updateSchema($this->metadatas);
            return "Success: tables created";
        } catch (Exception $e) {
            return "Failed";
        }
    }

    public function deleteTable(): String {
        try {
            $this->schemaTool->dropSchema($this->metadatas);
            return "Success: all tables deletes";
        } catch (Exception $e) {
            return "Failed";
        }
    }

    public function fetchAll(String $tableName): array {
        try {
            $result = $this->manager->getRepository($tableName)->findAll();
            return $result;
        } catch (\Exception $e) {
            return [];  
        }
    }

    public function newEntity($entity): bool {
        try {
            $this->manager->persist($entity);
            $this->manager->flush();
            return true;
        } catch (UniqueConstraintViolationException $e) {
            return false;
        }
    }

}
