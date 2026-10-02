<?php

namespace App\E06Bundle\Controller;

use App\E03Bundle\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted('ROLE_USER')]
class EditPost extends AbstractController {

    #[Route(path:'/e06/edit/{postId}', name:'e06_post_edit')]
    public function editPost(int $postId, EntityManagerInterface $manager): Response {
        $post = $manager->getRepository(Post::class)->find($postId);
        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }
        
    }
}