<?php

namespace App\Controller;

use App\Form\NotesType;
use App\Model\NotesModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ex02Controller extends AbstractController
{
    #[Route('/e02', name: 'ex02_main')]
    public function index(Request $request, string $notesFilePath): Response
    {
        $noteModel = new NotesModel();
        $form = $this->createForm(NotesType::class, $noteModel);
        $form->handleRequest($request);
        $lastLineAdded = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $message = $noteModel->message;
            
            if ($noteModel->includeTimestamp) {
                $timestamp = (new \DateTime())->format('Y-m-d H:i:s');
                $line = sprintf('%s - %s', $timestamp, $message);
            } else {
                $line = $message;
            }

            $filesystem = new Filesystem();
            $filesystem->appendToFile($notesFilePath, $line . PHP_EOL);

            $lastLineAdded = $line;
        }

        return $this->render('ex02/index.html.twig', [
            'form' => $form->createView(),
            'last_line' => $lastLineAdded,
        ]);
    }
}