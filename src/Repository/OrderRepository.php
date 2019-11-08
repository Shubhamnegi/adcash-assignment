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
    public function findOrderByUser($userId, $limit, $skip)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.user_id = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('o.create_at', 'DESC')
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
            ->andWhere('o.product_id = :productId')
            ->setParameter('productId', $productId)
            ->orderBy('o.create_at', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($skip)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $userId
     * @param $productId
     * @param $quantity
     * @param $total
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createOrder($userId, $productId, $quantity, $total)
    {
        $em = $this->getEntityManager(); // entity manager

        $current = new \DateTime(); // current time

        $order = new Order();
        $order->setCreatedAt($current);
        $order->setProductId($productId);
        $order->setUserId($userId);
        $order->setQuantity($quantity);
        $order->setTotal($total);

        $em->persist($order);
        $em->flush();

    }


}
