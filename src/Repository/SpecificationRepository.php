<?php

namespace App\Repository;

use App\Entity\Specification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Specification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Specification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Specification[]    findAll()
 * @method Specification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Specification::class);
    }

    /**
     * @return int|mixed[]|string
     */
    public function findColorSpecifications()
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT DISTINCT e.name as color, e.slug
            FROM App\Entity\Specification e
            WHERE e.name =:type AND e.active = TRUE
            ORDER BY e.name ASC'
        )->setParameter('type', 'color')->getArrayResult();
    }
}
