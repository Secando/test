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
#[Table('email_queue')]
#[HasLifecycleCallbacks]
class EmailQueue
{
    use EntitySetCreatedAtTrait;
    #[Id]
    #[Column, GeneratedValue]
    private int|null $id ;

    #[Column(name: 'email_to')]
    private string $emailTo;
    #[Column(name: 'email_from')]
    private string $emailFrom;

    #[Column(name: 'header')]
    private string $header;
    #[Column(name: 'body')]
    private string $body;

    #[Column(name: 'status')]
    private int $status;

    #[Column(name: 'created_at')]
    private string $createdAt;



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
    public function getEmailTo(): string
    {
        return $this->emailTo;
    }

    /**
     * @param string $emailTo
     */
    public function setEmailTo(string $emailTo): void
    {
        $this->emailTo = $emailTo;
    }

    /**
     * @return string
     */
    public function getEmailFrom(): string
    {
        return $this->emailFrom;
    }

    /**
     * @param string $emailFrom
     */
    public function setEmailFrom(string $emailFrom): void
    {
        $this->emailFrom = $emailFrom;
    }

    /**
     * @return string
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @param string $header
     */
    public function setHeader(string $header): void
    {
        $this->header = $header;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param string $status
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
     * @return string
     */

    /**
     * @param string $to
     */






}