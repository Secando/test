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
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('products')]
#[ORM\HasLifecycleCallbacks]
class Product
{
    use EntitySetCreatedAtTrait;
    #[Id]
    #[Column, GeneratedValue]
    private ?int $id = null;

    #[Column(name: 'english_name')]
    private string $englishName;
    #[Column(name: 'rus_name')]
    private string $rusName;

    #[Column(name: 'description', nullable: true)]
    private ?string $description;

    #[Column(name: 'page_count', nullable: true)]
    private ?string $pageCount;

    #[Column(name: 'isbn', nullable: true)]
    private ?string $isbn;

    #[Column(name: 'author', nullable: true)]
    private ?string $author;

    #[Column(name: 'cost')]
    private int $cost;

    #[Column(name: 'image_path', nullable: true)]
    private ?string $imagePath;

    #[Column(name: 'url', nullable: true)]
    private string $url;

    #[OneToMany(mappedBy: 'product', targetEntity: Comment::class)]
    private Collection $comments;

    #[OneToMany(mappedBy: 'product', targetEntity: Favorite::class)]
    private Collection $favorites;


    #[OneToMany(mappedBy: 'product', targetEntity: Order::class)]
    private Collection $orders;


    public function __construct()
    {

        $this->favorites = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }
    // Геттеры и сеттеры для отношений
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setProduct($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // Разрываем связь между заказом и продуктом
            if ($order->getProduct() === $this) {
                $order->setProduct(null);
            }
        }

        return $this;
    }

    public function addFavorite(Favorite $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
            $favorite->setProduct($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): self
    {
        if ($this->favorites->contains($favorite)) {
            $this->favorites->removeElement($favorite);
            // Разрываем связь между избранным и продуктом
            if ($favorite->getProduct() === $this) {
                $favorite->setProduct(null);
            }
        }

        return $this;
    }
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setProduct($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // Разрываем связь между комментарием и продуктом
            if ($comment->getProduct() === $this) {
                $comment->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }




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

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getPageCount(): string
    {
        return $this->pageCount;
    }

    /**
     * @param string $pageCount
     */
    public function setPageCount(string $pageCount): void
    {
        $this->pageCount = $pageCount;
    }

    /**
     * @return string
     */
    public function getIsbn(): string
    {
        return $this->isbn;
    }

    /**
     * @param string $isbn
     */
    public function setIsbn(string $isbn): void
    {
        $this->isbn = $isbn;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getCost(): string
    {
        return $this->cost;
    }

    /**
     * @param string $cost
     */
    public function setCost(string $cost): void
    {
        $this->cost = $cost;
    }

    /**
     * @return string
     */
    public function getImagePath(): string
    {
        return $this->imagePath;
    }

    /**
     * @param string $imagePath
     */
    public function setImagePath(string $imagePath): void
    {
        $this->imagePath = $imagePath;
    }

    /**
     * @return mixed
     */
    public function getTagsProduct()
    {
        return $this->tagsProduct;
    }

    /**
     * @param mixed $tagsProduct
     */
    public function setTagsProduct($tagsProduct): void
    {
        $this->tagsProduct = $tagsProduct;
    }

    /**
     * @return mixed
     */
    public function getProductComments()
    {
        return $this->productComments;
    }

    /**
     * @param mixed $productComments
     */
    public function setProductComments($productComments): void
    {
        $this->productComments = $productComments;
    }

    /**
     * @return string
     */
    public function getEnglishName(): string
    {
        return $this->englishName;
    }

    /**
     * @param string $englishName
     */
    public function setEnglishName(string $englishName): void
    {
        $this->englishName = $englishName;
    }

    /**
     * @return string
     */
    public function getRusName(): string
    {
        return $this->rusName;
    }

    /**
     * @param string $rusName
     */
    public function setRusName(string $rusName): void
    {
        $this->rusName = $rusName;
    }






}