<?php

namespace App\Controllers;

use App\Auth;
use App\Services\ProductService;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;

class CatalogController
{
    public function __construct(private EntityManager $em, private Twig $twig, private ProductService $productService,private Auth $auth)
    {
    }

    public function index(RequestInterface $request, ResponseInterface $response, $args)
    {
        return $response->withStatus(302)->withHeader('Location', '/catalog/');
    }

    public function filter(RequestInterface $request, ResponseInterface $response, $args)
    {
        $filters = $request->getQueryParams();
        if (key_exists('page', $filters)) {
            if (!(int)$filters['page']) {
                return $response->withStatus(302)->withHeader('Location', '/catalog/?page=1');
            }
        } else {
            return $response->withStatus(302)->withHeader('Location', '/catalog/?page=1');
        }
        $products = $this->productService->pagination($filters);
        $pages = $this->productService->getPages() / 4;
        return $this->twig->render(
            $response,
            'catalog.twig',
            ['products' => $products, 'pages' => ['all' => range(1, ceil($pages))]]
        );
    }

    public function showProduct(RequestInterface $request, ResponseInterface $response, $args)
    {
        $positionFirst = strrpos($args['name'], '-');
        $positionLast = strlen($args['name']);
        $id = substr($args['name'], -($positionLast - 1 - $positionFirst));

        $product = $this->productService->getByIdObject($id);
        $isLogin = $this->auth->check();
        $inCart = $isLogin ? $this->productService->isInCart($id): false;
        $inFavorite = $isLogin ? $this->productService->isInFavortie($id) : false;

        return $this->twig->render(
            $response,
            'product.twig',
            ['product' => $product, 'inCart' => $inCart, 'inFavorite' => $inFavorite,'isLogin'=>$isLogin]
        );
    }

}