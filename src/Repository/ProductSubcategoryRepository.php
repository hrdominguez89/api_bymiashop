<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductSubcategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductSubcategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductSubcategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductSubcategory[]    findAll()
 * @method ProductSubcategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductSubcategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductSubcategory::class);
    }
}
