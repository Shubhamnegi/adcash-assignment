<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     *
     * @param $userId
     * @param $limit
     * @param $skip
     * @return Order[]
     */
    public function findOrderByUserId($userId, $limit, $skip)
    {
        return $this->createQueryBuilder('o')
            ->select("o.id,o.quantity,o.total,o.created_at")
            ->addSelect("u.name as user_name")
            ->addSelect("p.name as product_name")
            ->innerJoin("o.user", "u")
            ->innerJoin("o.product", "p")
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('o.created_at', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($skip)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $productId
     * @param $limit
     * @param $skip
     * @return Order[]
     */
    public function findOrderByProductId($productId, $limit, $skip)
    {
        return $this->createQueryBuilder('o')
            ->select("o.id,o.quantity,o.total,o.created_at")
            ->addSelect("u.name as user_name")
            ->addSelect("p.name as product_name")
            ->innerJoin("o.user", "u")
            ->innerJoin("o.product", "p")
            ->andWhere('p.id = :productId')
            ->setParameter('productId', $productId)
            ->orderBy('o.created_at', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($skip)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $user
     * @param $product
     * @param $quantity
     * @param $total
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createOrder($user, $product, $quantity, $total)
    {
        $em = $this->getEntityManager(); // entity manager

        $current = new \DateTime(); // current time

        $order = new Order();
        $order->setCreatedAt($current);
        $order->setProduct($product);
        $order->setUser($user);
        $order->setQuantity($quantity);
        $order->setTotal($total);

        $em->persist($order);
        $em->flush();

    }


}
