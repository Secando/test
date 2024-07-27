<?php

namespace App\Services;

use App\Auth;
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

class OrderService
{
    public function __construct(
        private EntityManager $em,
        private readonly Session $session,
        private readonly Filesystem $filesystem,
        private readonly Auth $auth
    ) {
    }


    public function getAll()
    {
        $userId = $this->auth->check()['id'];
        $user = $this->em->getRepository(User::class)->find($userId);
        $products = $user->getOrders()->toArray();
        $orders = [];
        foreach ($products as $product) {
            $orderId = $product->getOrderId();
            if (array_key_exists($orderId, $orders)) {
                $orders[$orderId]['product'][] = $product->getProduct();
            } else {
                $orders[$orderId] = ['product'=>[$product->getProduct()],'order'=>$product];
            }

        }
        return $orders;
    }

    public function createOrder()
    {
        $userId = $this->auth->check()['id'];
        $ids = $this->session->get('cart');
        $user = $this->em->getRepository(User::class)->find($userId);
        $countOrders = $user->getOrders()->count();
        foreach ($ids as $id) {
            $product = $this->em->getRepository(Product::class)->find($id);
            $order = new Order();
            $order->setUser($user);
            $order->setProduct($product);
            $order->setStatus(OrderStatus::PENDING->value);
            $order->setStatusPayment(PaymentStatus::WAITING_PAYMENT->value);
            $order->setOrderId(('1' . $userId) . '00' . array_sum($ids) . '-' . (1000 + $countOrders));
            $this->em->persist($order);
        }
        $this->em->flush();
        $this->session->remove('cart');
    }

    public function getAllForAdmin()
    {

        $products = $this->em->getRepository(Order::class)->findAll();
        $orders =[];
        foreach ($products as $product) {
            $orderId = $product->getOrderId();
            if (array_key_exists($orderId, $orders)) {
                $orders[$orderId]['product'][] = $product->getProduct();
            } else {
                $orders[$orderId] = ['product'=>[$product->getProduct()],'order'=>$product];
            }

        }
        return $orders;
    }

    public function updateStatus(mixed $data)
    {
        $orders = $this->em->getRepository(Order::class)->findBy(['orderId'=>$data['orderId']]);
        foreach ($orders as $order){
            $order->setStatus((int) $data['status']);
            $this->em->persist($order);
            $this->em->flush();
        }
    }

    public function delete(object|array|null $data)
    {
        $orders = $this->em->getRepository(Order::class)->findBy(['orderId'=>$data['orderId']]);
        foreach ($orders as $order){
            $this->em->remove($order);
            $this->em->flush();
        }
    }


}