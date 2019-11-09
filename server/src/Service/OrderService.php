<?php


namespace App\Service;


use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class OrderService
{

    private $em;
    private $logger;

    /**
     * OrderService constructor.
     * @param $em
     * @param $logger
     */
    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    /**
     * @param $orderId
     * @return Order|Object
     */
    public function getOrderByOrderId($orderId)
    {
        if (!isset($orderId)) {
            throw new BadRequestHttpException("missing order id");
        }
        $em = $this->em->getRepository(Order::class);
        $order = $em->find($orderId);
        if (!$order) {
            throw  new BadRequestHttpException("Invalid order id");
        }
        return $order;
    }

    /**
     * @param $getBy string To get order by user or product
     * @param $id
     * @param int $limit
     * @param int $skip
     * @return Order[]
     */
    public function listOrdersById($getBy, $id, $limit = 10, $skip = 0)
    {
        // Validate request
        if (!isset($getBy) || !isset($id)) {
            throw  new BadRequestHttpException("Missing parameters");
        }

        $em = $this->em->getRepository(Order::class);
        if ($getBy == "user") {
            $orders = $em->findOrderByUserId($id, $limit, $skip);
        } elseif ($getBy == "product") {
            $orders = $em->findOrderByProductId($id, $limit, $skip);
        } else {
            throw  new BadRequestHttpException("Invalid parameter");
        }
        return $orders;
    }

    /**
     * @param $getBy string To get order by user or product
     * @param $name
     * @param int $limit
     * @param int $skip
     * @return Order[]
     */
    public function listOrdersByName($getBy, $name, $limit = 10, $skip = 0)
    {
        // Validate request
        if (!isset($getBy) || !isset($name)) {
            throw  new BadRequestHttpException("Missing parameters");
        }


        $em = $this->em->getRepository(Order::class);
        if ($getBy == "user") {
            $orders = $em->findOrderByUserName($name, $limit, $skip);
        } elseif ($getBy == "product") {
            $orders = $em->findOrdersByProductName($name, $limit, $skip);
        } else {
            throw  new BadRequestHttpException("Invalid parameter");
        }
        return $orders;
    }

    public function countOrderByName($getBy, $name = "")
    {
        if (!isset($name)) {
            throw  new BadRequestHttpException("missing parameter name");
        }
        $em = $this->em->getRepository(Order::class);

        if ($getBy == "user") {
            $result = $em->countOrderByUserName($name);
        } elseif ($getBy == "product") {
            $result = $em->countOrderByProductName($name);
        } else {
            throw  new BadRequestHttpException("Invalid parameter");
        }

        return $result;
    }


    /**
     * @param $userId
     * @param $productId
     * @param $quantity
     */
    public function createOrder($userId, $productId, $quantity)
    {
        $userRepo = $this->em->getRepository(User::class);
        $productRepo = $this->em->getRepository(Product::class);
        $orderRepo = $this->em->getRepository(Order::class);

        $user = $userRepo->find($userId);
        if (!$user) {
            throw  new BadRequestHttpException("Invalid user id");
        }
        $product = $productRepo->find($productId); // Get Product by id
        if (!$product) {
            throw  new BadRequestHttpException("Invalid product id");
        }
        // Get product attributes
        $totalPrice = $quantity * $product->getUnitPrice();
        $discount = $product->getDiscount();
        $discountType = $product->getDiscountType();

        // Check if product has discount and min required quantity is matched
        if (isset($discountType) && isset($discount) && $quantity >= $product->getMinQtyForDiscount()) {
            $this->logger->debug("Will be applying discount as the min condition matches");
            if ($discountType == "flat") {
                $this->logger->debug("Applying flat discount of " . $discount);
                $totalPrice = $totalPrice - $discount;
            } else if ($discountType == "percent") {
                $this->logger->debug("Applying percent discount of " . $discount);
                $totalPrice = $totalPrice - (($discount / 100) * $totalPrice);
            }
            if ($totalPrice < 0) {
                // This should never happen.
                // This condition is for just in case someone messes up inventory
                $totalPrice = 0;
            }
        }
        // Create Order
        $orderRepo->createOrder($user, $product, $quantity, $totalPrice);
    }
}