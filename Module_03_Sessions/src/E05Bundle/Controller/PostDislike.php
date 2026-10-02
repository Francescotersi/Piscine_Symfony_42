<?php

namespace App\E05Bundle\Controller;

use App\Entity\User;
use App\E03Bundle\Entity\Post;
use App\E05Bundle\Entity\PostVote;
use App\E02Bundle\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted('ROLE_USER')]
class PostDislike extends AbstractController
{
    #[Route(path: '/e05/dislike/{postId}', name: 'e05_post_dislike')]
    public function dislikePost(int $postId, EntityManagerInterface $manager, Request $request): Response
    {
        $post = $manager->getRepository(Post::class)->find($postId);
        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        $user = $this->getUser();
        if (!$user instanceof User && !$user instanceof Admin) {
            throw $this->createAccessDeniedException('You must be logged in to like a post.');
        }

        $voteRepo = $manager->getRepository(PostVote::class);
        if ($user instanceof User && $user->getReputation() >= 6) {
            $existingVote = $voteRepo->findOneBy(['post' => $post, 'user' => $user]);
        } else {
            if ($user instanceof Admin) {
                $existingVote = $voteRepo->findOneBy(['post' => $post, 'admin' => $user]);
            } else {
                $this->addFlash('error', 'You must have atleast 6 reputation to dislike posts');
                return $this->redirectToRoute('e03_post_show', ['id' => $postId]);
            }
        }
        if ($existingVote) {
            if ($existingVote->getType() === 'DISLIKE') {
                $manager->remove($existingVote);
            } else {
                $existingVote->setType('DISLIKE');
            }
        } else {
            $like = new PostVote();
            $like->setPost($post);
            $like->setType('DISLIKE');
            if ($user instanceof User) {
                $like->setUser($user);
            } else {
                $like->setAdmin($user);
            }
            $manager->persist($like);
        }

        $manager->flush();
        $referer = $request->headers->get('referer');
        return $referer ? $this->redirect($referer) : $this->redirectToRoute('e03_post_show', ['postId' => $postId]);
    }
}