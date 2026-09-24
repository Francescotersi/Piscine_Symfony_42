<?php

namespace App\E03Bundle\Controller;

use App\E03Bundle\Entity\Post;
use App\E03Bundle\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PostController extends AbstractController
{
    #[Route('/e03/post/new', name: 'e03_post_new')]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuthor($this->getUser());
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('e01_home');
        }
        return $this->render('e03/post/newPost.html.twig', [
            'postForm' => $form->createView(),
        ]);
    }

    #[Route('/e03/post/{id}', name: 'e03_post_show')]
    #[IsGranted('ROLE_USER')]
    public function show(int $id, EntityManagerInterface $em): Response
    {
        $post = $em->getRepository(Post::class)->find($id);
        if (!$post) {
            throw $this->createNotFoundException('Post not found.');
        }
        return $this->render('e03/post/showPost.html.twig', [
            'post' => $post,
        ]);
    }
}