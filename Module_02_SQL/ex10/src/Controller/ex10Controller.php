<?php

namespace App\Controller;

use App\Entity\ORMTable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;


class ex10Controller extends AbstractController {

    public function __construct(private Connection $connection) {}

    #[Route(path:'/new', name:'ex10_newTable')]
    public function newTable(EntityManagerInterface $manager): Response {
        try {
            $sql = '                
                CREATE TABLE IF NOT EXISTS "SQL_table" (
                id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                username VARCHAR(255) NOT NULL
            )';

            $this->connection->executeStatement($sql);
            $this->addFlash('success', 'SQL_table table has been created');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error while creating SQL_table table: ' . $e->getMessage());
        }
        try{
            $schemaTool = new SchemaTool($manager);
            $metadata = $manager->getClassMetadata(ORMTable::class);
            $schemaTool->updateSchema([$metadata]);
            $this->addFlash('success', 'SQL_table table has been created');
        }
        catch (\Exception $e) {
            $this->addFlash('error', 'Error while creating SQL_table table: ' . $e->getMessage());
        }
        return new Response("Success: Tables created successfully");
    }

    #[Route(path:'/read', name:'ex10_readFile')]
    public function readFile(Request $request, EntityManagerInterface $em): Response {
        if ($request->isMethod('POST')) {
            $file = $request->files->get('txtFile');

            if ($file && $file->getClientOriginalExtension() === 'txt') {
                $filePath = $file->getPathname();
                if (!is_readable($filePath)) {
                    $this->addFlash('error', 'Error: the uploaded file is not readable due to missing permissions.');
                    return $this->render('read_file.html.twig');
                }

                $content = file_get_contents($filePath);
                $rawUsernames = explode("\n", str_replace("\r", "", trim($content)));
                $usernames = [];
                foreach ($rawUsernames as $u) {
                    $u = trim($u);
                    if (!empty($u)) {
                        $usernames[] = $u;
                    }
                }
                $usernames = array_unique($usernames);
                try {
                    $this->connection->executeStatement('
                        CREATE TABLE IF NOT EXISTS "SQL_table" (
                            id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                            username VARCHAR(255) NOT NULL
                        )
                    ');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Impossibile creare la tabella: ' . $e->getMessage());
                }

                foreach ($usernames as $username) {
                    $username = trim($username);
                    if (empty($username)) continue;

                    try {
                        $this->connection->executeStatement(
                            'INSERT INTO "SQL_table" (username) VALUES (:username)',
                            ['username' => $username]
                        );
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'Raw SQL Error: ' . $e->getMessage());
                    }

                    $existing = $em->getRepository(ORMTable::class)->findOneBy(['username' => $username]);
                    if (!$existing) {
                        $ormEntry = new ORMTable();
                        $ormEntry->setUsername($username);
                        $em->persist($ormEntry);
                    }
                }
                try {
                    $em->flush();
                    $this->addFlash('success', "usernames successfully inserted into both tables!");
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Error during ORM flush: ' . $e->getMessage());
                }
            } else {
                $this->addFlash('error', 'Please upload a valid .txt file.');
            }
        }
        return $this->render('read_file.html.twig');
    }

    #[Route(path:'/list', name:'ex10_listTables')]
    public function listTables(EntityManagerInterface $em): Response {
        try {
            $sqlData = $this->connection->fetchAllAssociative('SELECT * FROM "SQL_table" ORDER BY id ASC');
        } catch (\Exception $e) {
            $sqlData = []; 
        }
        $ormData = $em->getRepository(ORMTable::class)->findAll();
        return $this->render('list_tables.html.twig', [
            'sql_data' => $sqlData,
            'orm_data' => $ormData,
        ]);
    }

}