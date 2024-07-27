<?php

namespace App\Services;

use App\Auth;
use App\Entity\Product;
use App\Entity\User;
use App\Exceptions\ValidationException;
use App\Session;
use app\Validations\ProductValidation;
use app\Validations\UserChangePasswordValidation;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use League\Flysystem\Filesystem;

class CartService
{
    public function __construct(
        private EntityManager $em,
        private readonly Session $session,
        private readonly Filesystem $filesystem,
    ) {
    }

    public function addToCart($id)
    {
        $this->session->push('cart', $id);
    }

    public function getAll()
    {
        $products = [];
        $ids = $this->session->get('cart');

        if (empty($ids)) {
            return [];
        }
        foreach ($ids as $id) {
            $products[] = $this->em->createQueryBuilder()->select('p')->from(Product::class, 'p')->where(
                'p.id = :id'
            )->setParameter(
                'id',
                $id
            )->getQuery()->getArrayResult()[0];
        }

        return $products;
    }

    public function deleteFromCart(mixed $id)
    {
        $this->session->removeFromArr('cart', $id);
    }


}