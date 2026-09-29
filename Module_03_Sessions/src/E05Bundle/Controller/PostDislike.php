<?php

namespace App\E05Bundle\Controller;

use App\Entity\User;
use App\E03Bundle\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// al momento quando uno user/admin mette like/dislike e poi viene cancellato l`account il lik/dislike rimane, quindi bisogna fare un controllo per vedere se l`id 
// dell`utente esiste ancora, se non esiste allora rimuovere il like/dislike rimane

// SOLUZIONE: crea Entity PostVote con campi is Post $post, User $user, Admin $admin, string $voteType (like/dislike) e 
//            gestisci i like/dislike in questa tabella invece che in un array di stringhe

    // #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'votes')]
    // #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    // private ?Post $post = null;
    // #[ORM\ManyToOne(targetEntity: User::class)]
    // #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    // private ?User $user = null;                                      PROTOTIPO DI CLASSE POSTVOTE
    // #[ORM\ManyToOne(targetEntity: Admin::class)]
    // #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    // private ?Admin $admin = null;
    // #[ORM\Column(type: 'string', length: 10)]
    // private string $type;
#[IsGranted('ROLE_USER')]
class PostDislike extends AbstractController
{
    #[Route(path: '/e05/dislike/{postId}', name: 'e05_post_dislike')]
    public function dislikePost(int $postId, EntityManagerInterface $em, Request $request): Response
    {
        $post = $em->getRepository(Post::class)->find($postId);
        if (!$post) {
            throw $this->createNotFoundException('Post not found.');
        }
        $user = $this->getUser();
        $voterId = ($user instanceof User ? 'user_' : 'admin_') . $user->getId();
        $post->toggleDislike($voterId);
        $em->flush();
        $referer = $request->headers->get('referer');
        return $referer ? $this->redirect($referer) : $this->redirectToRoute('e03_post_show', ['postId' => $postId]);
    }
}