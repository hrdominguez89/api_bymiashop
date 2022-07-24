<?php

namespace App\Repository;

use App\Entity\Shopping;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Shopping|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shopping|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shopping[]    findAll()
 * @method Shopping[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoppingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shopping::class);
    }

    /**
     * @param $uid
     * @return array
     */
    public function findByUid($uid): array
    {
        $entityManager = $this->getEntityManager();

        $dql = 'SELECT e
            FROM App\Entity\Shopping e 
            LEFT JOIN e.customerId c
            WHERE c.id =:uid';

        return $entityManager->createQuery($dql)->setParameter('uid', $uid)->getResult();
    }

    /**
     * @param $uid
     * @param $oid
     * @return array
     */
    public function findByUidOrderId($uid, $oid): array
    {
        $entityManager = $this->getEntityManager();

        $dql = 'SELECT e
            FROM App\Entity\Shopping e 
            LEFT JOIN e.customerId c
            LEFT JOIN e.shoppingOrderId o
            WHERE c.id =:uid AND o.id =:oid';

        return $entityManager
            ->createQuery($dql)
            ->setParameters(['uid'=> $uid, 'oid' => $oid])
            ->getResult();
    }

    /**
     * @param $uid
     * @param array $ids
     * @return array
     */
    public function findByUidIds($uid, array $ids): array
    {
        $entityManager = $this->getEntityManager();

        $dql = 'SELECT e
            FROM App\Entity\Shopping e 
            LEFT JOIN e.customerId c
            LEFT JOIN e.productId p
            WHERE c.id =:uid AND p.id IN (:ids)';

        return $entityManager
            ->createQuery($dql)
            ->setParameter('uid', $uid)
            ->setParameter('ids', $ids)
            ->getResult();
    }

    /**
     * @param $newIds
     * @return int|mixed|string
     */
    public function findNewProduct($newIds)
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT e
            FROM App\Entity\Product e
            WHERE e.id IN (:ids)'
        )->setParameter('ids', $newIds)->getResult();
    }

    /**
     * @param array $ids
     * @param array $oldIds
     * @return array
     */
    public function getNewIds(array $ids, array $oldIds): array
    {
        return count($oldIds) == 0 ? $ids : array_diff($ids, $oldIds);
    }
}
