<?php

namespace App\E05Bundle\Controller;

use App\E03Bundle\Entity\Post;
use App\Entity\User;
use App\E02Bundle\Entity\Admin;
use App\E05Bundle\Entity\PostVote;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted('ROLE_USER')]
class PostLike extends AbstractController {

    #[Route(path:'/e05/like/{postId}', name:'e05_post_like')]
    public function likePost(int $postId, EntityManagerInterface $manager, Request $request): Response {
        $post = $manager->getRepository(Post::class)->find($postId);
        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        $user = $this->getUser();
        if (!$user instanceof User && !$user instanceof Admin) {
            throw $this->createAccessDeniedException('You must be logged in to like a post.');
        }

        $voteRepo = $manager->getRepository(PostVote::class);
        if ($user instanceof User && $user->getReputation() >= 3) {
            $existingVote = $voteRepo->findOneBy(['post' => $post, 'user' => $user]);
        } else {
            if ($user instanceof Admin) {
                $existingVote = $voteRepo->findOneBy(['post' => $post, 'admin' => $user]);
            } else {
                $this->addFlash('error', 'You must have atleast 3 reputation to like posts');
                return $this->redirectToRoute('e03_post_show', ['id' => $postId]);
            }
        }
        if ($existingVote) {
            if ($existingVote->getType() === 'LIKE') {
                $manager->remove($existingVote);
            } else {
                $existingVote->setType('LIKE');
            }
        } else {
            $like = new PostVote();
            $like->setPost($post);
            $like->setType('LIKE');
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