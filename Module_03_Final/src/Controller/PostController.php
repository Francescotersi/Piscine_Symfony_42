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
    public function defaultAction(EntityManagerInterface $manager): Response {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        return $this->render('index.html.twig', [
            'postForm' => $form->createView(),
            'posts' => $manager->getRepository(Post::class)->findAll(),
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

            return new JsonResponse(['success' => true, 'message' => 'Post was successfully added', 'post' => [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'created' => $post->getCreated()->format('Y-m-d H:i:s'),
            ]], Response::HTTP_CREATED);
        }
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        $errorMessage = !empty($errors) ? $errors[0] : 'Invalid form data.';
        return new JsonResponse([
            'success' => false,
            'error' => $errorMessage
        ], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/view/{id}', name: 'view_post')]
    public function viewAction(int $id, EntityManagerInterface $manager): Response {
        $post = $manager->getRepository(Post::class)->find($id);
        if (!$post) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Post not found.'
            ], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse(['success' => true, 'message' => 'Post was successfully fetched', 'post' => [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'author' => $post->getAuthor()->getUsername(),
                'content' => $post->getContent(),
                'created' => $post->getCreated()->format('Y-m-d H:i:s'),
        ]], Response::HTTP_OK);
    }

    #[Route('/delete/{id}', name: 'delete_post', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function deleteAction(int $id, EntityManagerInterface $manager): Response {
        $post = $manager->getRepository(Post::class)->find($id);
        if (!$post) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Post not found.'
            ], Response::HTTP_NOT_FOUND);
        }
        $manager->remove($post);
        $manager->flush();
        return new JsonResponse([
            'success' => true,
            'message' => 'Post was successfully deleted'
        ], Response::HTTP_OK);
    }
}