<?php

namespace App\Controllers\Admin;

use App\Services\ProductService;
use App\Validations\ProductValidation;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class ProductController
{
    public function __construct(
        private EntityManager $em,
        private Twig $twig,
        private readonly ProductValidation $productValidation,
        private readonly ProductService $productService
    ) {
    }

    public function index(Request $request, Response $response, $args)
    {
        $products = $this->productService->getAll();
        return $this->twig->render($response, 'admin/product_index.twig', ['products' => $products]);
    }

    public function create(Request $request, Response $response, $args)
    {
        return $this->twig->render($response, 'admin/create_product.twig');
    }

    public function store(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $data['image_path'] = $request->getUploadedFiles()['image_path'];
        $this->productValidation->validate($data);
        $this->productService->store($data);
        return $response->withStatus(302)->withHeader('Location', '/admin/products');
    }

    public function edit(Request $request, Response $response, $args)
    {
        $product = $this->productService->getById($args['id']);
        return $this->twig->render($response, 'admin/edit_product.twig', ['product' => $product]);
    }

    public function update(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $data['image_path'] = $request->getUploadedFiles()['image_path']->getClientMediaType(
        ) ? $request->getUploadedFiles()['image_path'] : null;
        $this->productValidation->validate($data);
        $this->productService->update($args['id'], $data);
        return $response->withStatus(302)->withHeader('Location', '/admin/products');
    }

    public function delete(Request $request, Response $response, $args)
    {
        $this->productService->delete($args['id']);
        return $response->withStatus(302)->withHeader('Location', '/admin/products');
    }

}