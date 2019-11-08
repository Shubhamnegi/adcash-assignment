<?php


namespace App\Controller;

use App\Service\ProductService;
use App\Utility\CustomResponse;
use App\Utility\JsonHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class ProductController
{
    private $logger;
    private $productService;

    /**
     * ProductController constructor.
     * @param $logger
     * @param $productService
     */
    public function __construct(LoggerInterface $logger, ProductService $productService)
    {
        $this->logger = $logger;
        $this->productService = $productService;
    }


    public function listProducts()
    {
        $products = $this->productService->listProducts();
        $response = new CustomResponse(JsonHelper::toJson($products));
        return new JsonResponse(
            $response, 200
        );
    }

}