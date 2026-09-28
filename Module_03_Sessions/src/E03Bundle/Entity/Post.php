<?php

namespace App\E03Bundle\Entity;

use App\Entity\User;
use App\E02Bundle\Entity\Admin;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'posts')]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $title;
    
    #[ORM\Column(type: 'text')]
    private ?string $content;

    #[ORM\Column(type: 'datetime')]
    private DateTime $created;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_author_id', referencedColumnName: 'id', nullable: true, onDelete: 'CASCADE')]
    private ?User $authorUser = null;

    #[ORM\ManyToOne(targetEntity: Admin::class)]
    #[ORM\JoinColumn(name: 'admin_author_id', referencedColumnName: 'id', nullable: true, onDelete: 'CASCADE')]
    private ?Admin $authorAdmin = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $likes = [];

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $dislikes = [];


    public function __construct() {
        $this->created = new DateTime();
    }

    public function getCreated(): DateTime {
        return $this->created;
    }

    public function setCreated(DateTime $created): void {
        $this->created = $created;
    }

    public function getAuthor(): User | Admin | null {
        return $this->authorUser ?? $this->authorAdmin;
    }

    public function setAuthor(User | Admin $author): void {
        if ($author instanceof User) {
            $this->authorUser = $author;
            $this->authorAdmin = null;
        } elseif ($author instanceof Admin) {
            $this->authorAdmin = $author;
            $this->authorUser = null;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getLikes(): array
    {
        return $this->likes ?? [];
    }
    public function getDislikes(): array
    {
        return $this->dislikes ?? [];
    }
    public function getLikesCount(): int
    {
        return count($this->likes);
    }
    public function getDislikesCount(): int
    {
        return count($this->dislikes);
    }

    public function toggleLike(string $voterId): void
    {
        $this->dislikes = array_values(array_diff($this->dislikes, [$voterId]));
        if (in_array($voterId, $this->likes)) {
            $this->likes = array_values(array_diff($this->likes, [$voterId]));
        } else {
            $this->likes[] = $voterId;
        }
    }
    public function toggleDislike(string $voterId): void
    {
        $this->likes = array_values(array_diff($this->likes, [$voterId]));
        if (in_array($voterId, $this->dislikes)) {
            $this->dislikes = array_values(array_diff($this->dislikes, [$voterId]));
        } else {
            $this->dislikes[] = $voterId;
        }
    }
}