<?php

namespace App\E05Bundle\Controller;

use App\E03Bundle\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
 
// al momento quando uno user/admin mette like/dislike e poi viene cancellato l`account il lik/dislike rimane, quindi bisogna fare un controllo per vedere se l`id 
// dell`utente esiste ancora, se non esiste allora rimuovere il like/dislike rimane
#[IsGranted('ROLE_USER')]
class PostLike extends AbstractController {

    #[Route(path:'/e05/like/{postId}', name:'e05_post_like')]
    public function likePost(int $postId, EntityManagerInterface $manager, Request $request): Response {
        $post = $manager->getRepository(Post::class)->find($postId);
        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }
        $user = $this->getUser();
        $voterId = ($user instanceof User ? 'user_' : 'admin_') . $user->getId();
        $post->toggleLike($voterId);
        $manager->flush();
        $referer = $request->headers->get('referer');
        return $referer ? $this->redirect($referer) : $this->redirectToRoute('e03_post_show', ['id' => $postId]);
    }
}