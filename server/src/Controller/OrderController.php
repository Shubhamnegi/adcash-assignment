<?php


namespace App\Controller;


use App\Service\OrderService;
use App\Utility\CustomResponse;
use App\Utility\JsonHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class OrderController
{

    private $orderService;
    private $logger;

    /**
     * OrderController constructor.
     * @param $orderService
     * @param $logger
     */
    public function __construct(OrderService $orderService, LoggerInterface $logger)
    {
        $this->orderService = $orderService;
        $this->logger = $logger;
    }

    public function orderById($id)
    {
        $order = $this->orderService->getOrderByOrderId($id);
        $response = new CustomResponse(JsonHelper::toJson($order));
        return new JsonResponse($response, 200);
    }

    public function createOrder(Request $request)
    {
        $id = $request->request->get("id");
        $userId = $request->request->get("userId");
        $productId = $request->request->get("productId");
        $quantity = $request->request->get("quantity");

        $this->orderService->createOrder($id, $userId, $productId, $quantity);
        $response = new CustomResponse(true);
        return new JsonResponse($response);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function listOrdersByName(Request $request)
    {
        $duration = $request->get('duration', 1);
        $getBy = $request->get('getby', "user");
        $name = $request->get('name', "");
        $limit = $request->get('limit', 10);
        $skip = $request->get('skip', 0);

        $order = $this->orderService->listOrdersByName($getBy, $name, $duration, $limit, $skip);
        $count = $this->orderService->countOrderByName($getBy, $name, $duration);
        $response = new CustomResponse(JsonHelper::toJson($order), "Result for " . $getBy, $count);
        return new JsonResponse($response, 200);
    }
}