<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    /** @var PaginatorInterface $pagination */
    private $pagination;

    /**
     * @param ManagerRegistry $registry
     * @param PaginatorInterface $pagination
     */
    public function __construct(ManagerRegistry $registry, PaginatorInterface $pagination)
    {
        parent::__construct($registry, Order::class);

        $this->pagination = $pagination;
    }

    /**
     * @param $oId
     * @return int|mixed|string|null
     */
    public function findOneById($oId)
    {
        try {

            $entityManager = $this->getEntityManager();

            [$id, $cId] = [(is_numeric($oId) ? $oId : 0), $oId];

            return $entityManager->createQuery(
                'SELECT e
            FROM App\Entity\Order e
            WHERE e.id =:id OR e.checkoutId =:cId'
            )->setParameters(['id' => $id, 'cId' => $cId])->getOneOrNullResult();

        } catch (\Exception $ex) {

        }

        return null;
    }

    /**
     * @param $customer
     * @param $cId
     * @return Order|null
     */
    public function findOneByCustomerCheckoutId($customer, $cId): ?Order
    {
        return $this->findOneBy(['customerId' => $customer, 'checkoutId' => $cId]);
    }

    /**
     * @param $customerId
     * @param $page
     * @param $limit
     * @param null $query
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function findAllPartial(
        $customerId,
        $page,
        $limit,
        $query = null
    ): \Knp\Component\Pager\Pagination\PaginationInterface {
        $entityManager = $this->getEntityManager();

        $dql = 'SELECT e.id, e.checkoutId, e.date, e.status, e.checkoutStatus, e.total, e.quantity
            FROM App\Entity\Order e
            LEFT JOIN e.customerId c
            WHERE c.id =:id';

        if ($query) {
            $dql = $dql.' AND LOWER(e.checkoutId) LIKE LOWER(:query)';
        }

        $dql = $dql.' ORDER BY e.id DESC';

        $target = $entityManager->createQuery($dql)->setParameter('id', $customerId);

        if ($query) {
            $target = $target->setParameter('query', '%'.$query.'%');
        }

        return $this->pagination->paginate($target, $page, $limit);

    }
}
