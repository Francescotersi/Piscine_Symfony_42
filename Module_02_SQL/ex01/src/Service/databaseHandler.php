<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

class databaseHandler
{
    private SchemaTool $schemaTool;
    private array $metadatas;

    public function __construct(private EntityManagerInterface $manager)
    {
        $this->metadatas = $manager->getMetadataFactory()->getAllMetadata();
        $this->schemaTool = new SchemaTool($manager);
    }

    public function createSchema(): void
    {
        $this->schemaTool->updateSchema($this->metadatas);
    }

    public function dropSchema(): void
    {
        $this->schemaTool->dropSchema($this->metadatas);
    }
}
