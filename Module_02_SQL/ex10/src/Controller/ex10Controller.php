<?php

namespace App\Controller;

use App\Entity\ORMTable;
use PhpCsFixer\ConfigurationException\InvalidForEnvFixerConfigurationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;


class Ex10Controller extends AbstractController {

    public function __construct(private Connection $connection) {}

    #[Route(path:'/new', name:'ex10_newTable')]
    public function newTable(EntityManagerInterface $manager): Response {
        try {
            $sql = "                
                CREATE TABLE IF NOT EXISTS SQL_table (
                id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                username VARCHAR(255) NOT NULL
            )";

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
                $content = file_get_contents($file->getPathname());
                $usernames = explode("\n", str_replace("\r", "", trim($content)));

                foreach ($usernames as $username) {
                    $username = trim($username);
                    if (empty($username)) continue;

                    try {
                        $this->connection->executeStatement(
                            'INSERT INTO SQL_table (username) VALUES (:username)',
                            ['username' => $username]
                        );

                        $ormEntry = new ORMTable();
                        $ormEntry->setUsername($username);
                        $em->persist($ormEntry);

                    } catch (\Exception $e) {}
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

        return $this->render('ex10/read_file.html.twig');
    }

    // #[Route(path:'/list', name:'ex10_listTables')]
    // public function listTables(): Response {}
}