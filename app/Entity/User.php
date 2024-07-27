<?php
// src/User.php
namespace App\Entity;
use App\Traits\EntitySetCreatedAtTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('users')]
#[ORM\HasLifecycleCallbacks]

class User
{
    use EntitySetCreatedAtTrait;
    #[Id]
    #[Column, GeneratedValue]
    private ?int $id = null;

    #[Column(name: 'email', unique: true)]
    private string $email;

    #[Column(name: 'name')]
    private string $name;

    #[Column(name: 'password')]
    private string $password;

    #[Column(name: 'avatar_path', nullable: true)]
    private ?string $avatarPath;

    #[Column(name: 'role',nullable: true)]
    private ?int $role;


    #[Column(name: 'created_at')]
    private string $createdAt;

    #[Column(name: 'verificated_at', nullable: true)]
    private ?string $verificatedAt;

    #[OneToMany(mappedBy: 'user', targetEntity: Comment::class)]
    private Collection $comments;

    #[OneToMany(mappedBy: 'user', targetEntity: Favorite::class)]
    private Collection $favorites;

    #[OneToMany(mappedBy: 'user', targetEntity: Order::class)]
    private Collection $orders;

    public function __construct()
    {

        $this->comments = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // Разрываем связь между заказом и пользователем
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
            $favorite->setUser($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): self
    {
        if ($this->favorites->contains($favorite)) {
            $this->favorites->removeElement($favorite);
            // Разрываем связь между избранным и пользователем
            if ($favorite->getUser() === $this) {
                $favorite->setUser(null);
            }
        }

        return $this;
    }
    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // Разрываем связь между комментарием и пользователем
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getAvatarPath(): string
    {
        return $this->avatar_path;
    }

    /**
     * @param string $avatar_path
     */
    public function setAvatarPath(string $avatar_path): void
    {
        $this->avatar_path = $avatar_path;
    }

    /**
     * @return int
     */
    public function getVerificationCode(): int
    {
        return $this->verificationCode;
    }

    /**
     * @param int $verificationCode
     */
    public function setVerificationCode(int $verificationCode): void
    {
        $this->verificationCode = $verificationCode;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @param int $createdAt
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUserComments()
    {
        return $this->userComments;
    }

    /**
     * @param mixed $userComments
     */
    public function setUserComments($userComments): void
    {
        $this->userComments = $userComments;
    }

    /**
     * @return string
     */
    public function getVerificatedAt(): string|null
    {
        return $this->verificatedAt;
    }

    /**
     * @param string $verificatedAt
     */
    public function setVerificatedAt(string $verificatedAt): void
    {
        $this->verificatedAt = $verificatedAt;
    }

    /**
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @param string|null $role
     */
    public function setRole(?string $role): void
    {
        $this->role = $role;
    }






}