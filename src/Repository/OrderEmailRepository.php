<?php

namespace App\Repository;

use App\Entity\OrderEmail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderEmail|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderEmail|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderEmail[]    findAll()
 * @method OrderEmail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderEmailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderEmail::class);
    }
}
