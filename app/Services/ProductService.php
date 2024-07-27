<?php

namespace App\Services;

use App\Auth;
use App\Entity\Favorite;
use App\Entity\Product;
use App\Entity\User;
use App\Exceptions\ValidationException;
use App\Session;
use app\Validations\ProductValidation;
use app\Validations\UserChangePasswordValidation;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use League\Flysystem\Filesystem;

class ProductService
{
    public function __construct(
        private EntityManager $em,
        private readonly Auth $auth,
        private readonly Session $session,
        private readonly Filesystem $filesystem,
    ) {
    }

    public function store($data)
    {
        $product = new Product();
        $product->setEnglishName($data['english_name']);
        $product->setRusName($data['rus_name']);
        $product->setPageCount($data['page_count']);
        $product->setIsbn($data['isbn']);
        $product->setAuthor($data['author']);
        $product->setCost($data['cost']);

        $pathName = (new \DateTime())->format('Y-m-d H:i:s') . $data['english_name'] . '.png';
        $this->filesystem->write('/' . $pathName, (string)$data['image_path']->getStream());
        $product->setImagePath($pathName);
        $this->em->persist($product);
        $this->em->flush();
        $product->setUrl(preg_replace(
            '/[^a-z0-9\-\.]/',
            '',
            str_replace(' ', '-', strtolower($data['english_name'])
            ) . '-' . $product->getId()));
        $this->em->persist($product);
        $this->em->flush();
    }

    public function getAll()
    {
        return $this->em->getRepository(Product::class)->findAll();
    }

    public function getById(mixed $id)
    {
        return $this->em->getRepository(Product::class)->findBy(['id' => $id])[0];
    }
    public function getByIdObject(mixed $id)
    {
        return $this->em->getRepository(Product::class)->find($id);
    }

    public function update(mixed $id, $data)
    {
        $product = $this->em->getRepository(Product::class)->find($id);
        $product->setName($data['name']);
        $product->setPageCount($data['page_count']);
        $product->setIsbn($data['isbn']);
        $product->setAuthor($data['author']);
        $product->setCost($data['cost']);
        if ($data['image_path'] !== null) {
            $pathName = (new \DateTime())->format('Y-m-d H:i:s') . $data['name'] . '.png';
            $this->filesystem->write('/' . $pathName, (string)$data['image_path']->getStream());
            $product->setImagePath($pathName);
        }
        $this->em->persist($product);
        $this->em->flush();
    }

    public function delete(mixed $id)
    {
        $product = $this->em->getRepository(Product::class)->find($id);
        $this->em->remove($product);
        $this->em->flush();
    }

    public function pagination($filters)
    {

        $query = $this->em->createQueryBuilder();
        $searchTerm = key_exists('search', $filters) ? '%' . $filters['search'] . '%' : '%';

        $query->select('p')
            ->from(Product::class, 'p')
            ->where($query->expr()->like(
                $query->expr()->concat('p.englishName', $query->expr()->literal(' '), 'p.rusName'),
                ':search'
            ))
            ->setParameter('search', $searchTerm)
            ->andWhere('p.cost > :minCost')
            ->setParameter('minCost', key_exists('min_cost', $filters) ? (int) $filters['min_cost'] : 0)
            ->andWhere('p.cost < :maxCost')
            ->setParameter('maxCost', key_exists('max_cost', $filters) ? (int)$filters['max_cost'] : 99999)
            ->andWhere('p.pageCount > :min_pCount')
            ->setParameter('min_pCount', key_exists('min_pCount', $filters) ? (int) $filters['min_pCount'] : 0)
            ->andWhere('p.pageCount < :max_pCount')
            ->setParameter('max_pCount', key_exists('max_pCount', $filters) ? (int) $filters['max_pCount'] : 99999);
        if (key_exists('priceSort', $filters)) {
            $query->orderBy('p.cost', strtoupper($filters['priceSort']));
        }
        $currentPage = (int) $filters['page'] -1;
        $allPages = $this->getPages();

        $query->setFirstResult(0+ (4 * $currentPage));
        $query->setMaxResults(4+ (4 * $currentPage));

        return $query->getQuery()->getArrayResult();
    }
    public function getPages(){
        return $pages = ceil ($this->em->getRepository(Product::class)->count());
    }

    public function isInCart(string $id)
    {
        if($this->session->has('cart')){
        return key_exists($id,array_flip($this->session->get('cart')));
        }
    }

    public function isInFavortie(string $id)
    {
        $userId = $this->auth->check();
        if ($userId) {
            return (bool)$this->em->getRepository(Favorite::class)->findBy(['user' => $userId['id'], 'product' => $id]);
        }
            return false;
    }

}