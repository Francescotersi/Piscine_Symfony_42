<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PostController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function defaultAction(): Response {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        return $this->render('index.html.twig', [
            'postForm' => $form->createView(),
        ]);
    }

    #[Route('/post/new', name: 'post_new')]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $em): Response {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return new JsonResponse(['success' => false, 'error' => 'Invalid JSON.'], Response::HTTP_BAD_REQUEST);
        }
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuthor($this->getUser());

            $em->persist($post);
            $em->flush();

            return new JsonResponse(['success' => true, 'message' => 'Post was successfully added']);
        }
        return new JsonResponse([
            'success' => false,
            'error' => 'Invalid form data.'
        ], Response::HTTP_BAD_REQUEST);
    }
}