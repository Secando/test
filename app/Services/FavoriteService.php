<?php

namespace App\Services;

use App\Auth;
use App\Entity\Favorite;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Exceptions\ValidationException;
use App\Session;
use app\Validations\ProductValidation;
use app\Validations\UserChangePasswordValidation;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use League\Flysystem\Filesystem;

class FavoriteService
{
    public function __construct(
        private EntityManager $em,
        private readonly Session $session,
        private readonly Filesystem $filesystem,
        private readonly Auth $auth
    ) {
    }


    public function add($id)
    {
        $userId = $this->auth->check()['id'];
        $user = $this->em->getRepository(User::class)->find($userId);
        $product = $this->em->getRepository(Product::class)->find($id);
        foreach($user->getFavorites() as $v){
            if ($v->getUser()->getId() == $userId and $v->getProduct()->getId()==$id){
                $this->em->remove($v);
                $this->em->flush();
                return;
            }
        }

        $favorite = new Favorite();
        $product = $this->em->getRepository(Product::class)->find($id);
        $favorite->setUser($user);
        $favorite->setProduct($product);
        $this->em->persist($favorite);
        $this->em->flush();
    }

    public function getFavorite()
    {
        $userId = $this->auth->check()['id'];
        $user = $this->em->getRepository(User::class)->find($userId);
        $favorites = $user->getFavorites();
        $productIds=[];
        foreach ($favorites as $favorite){
            $productIds[] = $favorite->getProduct()->getId();
        }
        return $this->em->createQueryBuilder()->select('p')->from(Product::class,'p')->where('p.id IN (:ids)')->setParameter('ids',$productIds)->getQuery()->getArrayResult();
    }




}