<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

class databaseHandler {

    public function __construct(private EntityManagerInterface $manager,
                                private SchemaTool $schemaTool, private $metadatas) {
        $metadatas = $manager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($manager);
    }

    public function getAll(String $tableName): array {
        $result = $this->manager->getAll($tableName);
        return $result;
    }

    public function newEntity($entity): void {
        $this->manager->persist($entity);
        $this->manager->flush();        
    }

    public function newTable(): String {
        try {
            $this->schemaTool->updateSchema($this->metadatas);
            return "Sucess";
        } catch (\Exception $e) {
            return "Failed";
        }
    }

    public function deleteTable(): String {
        try {
            $this->schemaTool->dropSchema($this->metadatas);
            return "Success";
        } catch (\Exception $e) {
            return "Failed";
        }
    }
}