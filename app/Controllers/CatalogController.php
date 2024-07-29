<?php

namespace App\Controllers;

use App\Auth;
use App\Services\ProductService;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseFactoryInterface;

class CatalogController
{
    public function __construct(
        private EntityManager $em,
        private Twig $twig,
        private ProductService $productService,
        private Auth $auth,
        private ResponseFactoryInterface $responseFactory
    ) {
    }

    public function index(Request $request, Response $response, $args)
    {
        return $this->filter($request,$response,$args,'true');
    }

    public function filter(Request $request, Response $response, $args, $reset = false)
    {
        if(!$reset) {
            $filters = $request->getQueryParams();
            if (key_exists('page', $filters)) {
                if (!(int)$filters['page']) {
                    return $response->withStatus(302)->withHeader('Location', '/catalog/?page=1');
                }
            } else {
                $newQuery='';
                foreach ($request->getQueryParams() as $k=>$v){
                    $newQuery .= $k.'='.$v.'&';
                }
                return $response->withStatus(302)->withHeader('Location', '/catalog/?page=1'.'&'.rtrim($newQuery,'&'));
            }
        }else{
            $filters = ['page'=>1];
        }

        $products = $this->productService->pagination($filters);
        $pages = $this->productService->getPages() / 4;
        return $this->twig->render(
            $response,
            'catalog.twig',
            ['products' => $products, 'pages' => ['all' => range(1, ceil($pages))]]
        );
    }

    public function showProduct(Request $request, Response $response, $args)
    {
        $positionFirst = strrpos($args['name'], '-');
        $positionLast = strlen($args['name']);
        $id = substr($args['name'], -($positionLast - 1 - $positionFirst));

        $product = $this->productService->getByIdObject($id);
        $isLogin = $this->auth->check();
        $inCart = $isLogin ? $this->productService->isInCart($id) : false;
        $inFavorite = $isLogin ? $this->productService->isInFavortie($id) : false;

        return $this->twig->render(
            $response,
            'product.twig',
            ['product' => $product, 'inCart' => $inCart, 'inFavorite' => $inFavorite, 'isLogin' => $isLogin]
        );
    }

    public function drop(Request $request, Response $response, $args)
    {
        return $response->withStatus(302)->withHeader('Location', '/catalog');
    }


}