<?php

namespace App\E06Bundle\Controller;

use App\Entity\User;
use App\E03Bundle\Entity\Post;
use App\E03Bundle\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted('ROLE_USER')]
class EditPost extends AbstractController {

    #[Route(path:'/e06/edit/{postId}', name:'e06_post_edit')]
    public function editPost(int $postId, EntityManagerInterface $manager,Request $request): Response {
        $post = $manager->getRepository(Post::class)->find($postId);
        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }
//  se user
//      allora se user != admin e user != autore allora errore
//      altrimenti se user.reputation < 9 allora erro

        $user = $this->getUser();
        if ($user !== $post->getAuthor()) {
            if (!$this->isGranted('ROLE_ADMIN') && (!$user instanceof User || $user->getReputation() < 9)) {
                $this->addFlash('error', 'You must have at least 9 reputation to edit posts');
                return $this->redirectToRoute('e03_post_show', ['id' => $postId]);
            }
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setLastEditTime(new \DateTime());
            $post->setLastEditAuthor($this->getUser()->getUserIdentifier());
            $manager->persist($post);
            $manager->flush();
            return $this->redirectToRoute('e03_post_show', ['id' => $postId]);
        }

        return $this->render('e06/editPost.html.twig', [
            'postForm' => $form->createView(),
            'post' => $post,
        ]);

    }
}