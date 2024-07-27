<?php

namespace App\Traits;
use Doctrine\ORM\Mapping as ORM;
trait EntitySetCreatedAtTrait
{
    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = (new \DateTime())->format('Y-m-d H:i:s');
    }
}