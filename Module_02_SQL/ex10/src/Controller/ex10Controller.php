<?php

namespace App\Controller;

use App\Repository\ORMTableRepository;
use App\Service\SqlDatabaseManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ex10Controller extends AbstractController
{
    public function __construct(
        private SqlDatabaseManager $sqlManager,
        private ORMTableRepository $ormRepository
    ) {
    }

    #[Route(path: '/new', name: 'ex10_newTable')]
    public function newTable(): Response
    {
        try {
            $this->sqlManager->createTable();
            $this->addFlash('success', 'SQL_table table has been created');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error while creating SQL_table table: ' . $e->getMessage());
        }
        try {
            $this->ormRepository->createSchema();
            $this->addFlash('success', 'SQL_table table has been created');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error while creating SQL_table table: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex10_listTables');
    }

    #[Route(path: '/read', name: 'ex10_readFile')]
    public function readFile(Request $request): Response
    {
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
                    $this->sqlManager->createTable();
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Impossibile creare la tabella: ' . $e->getMessage());
                }

                foreach ($usernames as $username) {
                    $username = trim($username);
                    if (empty($username)) {
                        continue;
                    }

                    try {
                        $this->sqlManager->insertUser($username);
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'Raw SQL Error: ' . $e->getMessage());
                    }
                }

                try {
                    $this->ormRepository->saveUsernames($usernames);
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

    #[Route(path: '/list', name: 'ex10_listTables')]
    public function listTables(): Response
    {
        try {
            $sqlData = $this->sqlManager->getAllUsers();
        } catch (\Exception $e) {
            $sqlData = [];
        }
        $ormData = $this->ormRepository->getAll();
        return $this->render('list_tables.html.twig', [
            'sql_data' => $sqlData,
            'orm_data' => $ormData,
        ]);
    }
}