<?php

namespace App\Repository;

use App\Entity\Borrowing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Borrowing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Borrowing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Borrowing[]    findAll()
 * @method Borrowing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BorrowingRepository extends ServiceEntityRepository
{
    const PAGINATOR_ITEMS_PER_PAGE = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Borrowing::class);
    }

    /**
     * Save borrowing.
     *
     * @param \App\Entity\Borrowing $borrowing Borrowing entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Borrowing $borrowing): void
    {
        $this->_em->persist($borrowing);
        $this->_em->flush($borrowing);
    }

    /**
     * Delete borrowing.
     *
     * @param \App\Entity\Borrowing $borrowing Borrowing entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Borrowing $borrowing): void
    {
        $this->_em->remove($borrowing);
        $this->_em->flush($borrowing);
    }

    /**
     * Query all borrowings.
     *
     * @return QueryBuilder
     */
    public function queryAll()
    {
        return $this->getOrCreateQueryBuilder()
            ->select(
                'borrowing',
                'partial book.{id, title}'
            )
            ->innerJoin('borrowing.book', 'book');
    }

    /**
     * Get or create new query builder.
     *
     * @return QueryBuilder
     */
    public function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('borrowing');
    }
}
