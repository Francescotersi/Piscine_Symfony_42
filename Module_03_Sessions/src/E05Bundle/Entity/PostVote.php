<?php

namespace App\E05Bundle\Entity;

use App\Entity\User;
use App\E02Bundle\Entity\Admin;
use App\E03Bundle\Entity\Post;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

#[ORM\Entity]
#[ORM\Table(name: 'post_vote')]
class PostVote {
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Post::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Post $post = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Admin::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?Admin $admin = null;

    #[ORM\Column(type: 'string', length: 10)]
    private string $type;

    public function getAdmin(): ?Admin {
        return $this->admin;
    }

    public function setAdmin(?Admin $admin): self {
        $this->admin = $admin;

        return $this;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getType(): ?string {
        return $this->type;
    }

    public function setType(string $type): self {
        $this->type = $type;

        return $this;
    }

    public function getPost(): ?Post {
        return $this->post;
    }

    public function setPost(?Post $post): self {
        $this->post = $post;

        return $this;
    }

    public function getUser(): ?User {
        return $this->user;
    }

    public function setUser(?User $user): self {
        $this->user = $user;

        return $this;
    }
}


