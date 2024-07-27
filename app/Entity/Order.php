<?php

namespace App\Entity;

use App\Traits\EntitySetCreatedAtTrait;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('orders')]
#[HasLifecycleCallbacks]
class Order
{
    use EntitySetCreatedAtTrait;
    #[Id]
    #[Column, GeneratedValue]
    private int|null $id = null;

    #[Column(name: 'order_id')]
    private string $orderId;
    #[ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?User $user = null;

    #[ManyToOne(targetEntity: Product::class, inversedBy: 'orders')]
    #[JoinColumn(name: 'product_id', referencedColumnName: 'id')]
    private ?Product $product = null;
    #[Column(name: 'status')]
    private int $status;
    #[Column(name: 'status_payment')]
    private int $statusPayment;

    #[Column(name: 'track_number',nullable: true)]
    private string $trackNumber;




    #[Column(name: 'created_at')]
    private string $createdAt;


    /**
     * @return int|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;
        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getStatusPayment(): int
    {
        return $this->statusPayment;
    }

    /**
     * @param int $statusPayment
     */
    public function setStatusPayment(int $statusPayment): void
    {
        $this->statusPayment = $statusPayment;
    }

    /**
     * @return string
     */
    public function getTrackNumber(): string
    {
        return $this->trackNumber;
    }

    /**
     * @param string $trackNumber
     */
    public function setTrackNumber(string $trackNumber): void
    {
        $this->trackNumber = $trackNumber;
    }



    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @param int $orderId
     */
    public function setOrderId(string $orderId): void
    {
        $this->orderId = $orderId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getProductId(): string
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */







}