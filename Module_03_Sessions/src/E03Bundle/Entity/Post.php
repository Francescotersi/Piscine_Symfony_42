<?php

namespace App\E03Bundle\Entity;

use App\Entity\User;
use App\E02Bundle\Entity\Admin;
use App\E05Bundle\Entity\PostVote;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;

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

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: PostVote::class)]
    private Collection $votes;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $lastEditTime;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $lastEditAuthor;


    public function __construct() {
        $this->created = new DateTime();
        $this->votes = new ArrayCollection();
        $this->lastEditTime = null;
        $this->lastEditAuthor = null;
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

    public function getLikesCount(): int
    {
        $count = 0;
        foreach ($this->votes as $vote) {
            if ($vote->getType() === 'LIKE') {
                $count++;
            }
        }
        return $count;
    }
    public function getDislikesCount(): int
    {
        $count = 0;
        foreach ($this->votes as $vote) {
            if ($vote->getType() === 'DISLIKE') {
                $count++;
            }
        }
        return $count;
    }

    public function getLastEditTime(): ?DateTime {
        return $this->lastEditTime;
    }

    public function setLastEditTime(?DateTime $lastEditTime): void {
        $this->lastEditTime = $lastEditTime;
    }

    public function getLastEditAuthor(): ?string {
        return $this->lastEditAuthor;
    }

    public function setLastEditAuthor(?string $lastEditAuthor): void {
        $this->lastEditAuthor = $lastEditAuthor;
    }
}