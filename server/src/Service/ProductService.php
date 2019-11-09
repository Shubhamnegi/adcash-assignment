<?php


namespace App\Service;


use App\Entity\Product;
use App\Utility\JsonHelper;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{

    private $em;

    /**
     * ProductService constructor.
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

    }

    function listProducts()
    {
        $em = $this->em->getRepository(Product::class);
        $products = $em->findAllActiveProducts();
        return $products;
    }

}