<?php


namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{

    private $em;

    /**
     * UserService constructor.
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function listUsers()
    {
        $repo = $this->em->getRepository(User::class);
        $users = $repo->findAllActiveUsers();
        return $users;
    }
}