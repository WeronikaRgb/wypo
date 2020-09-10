<?php
/**
 * Borrowing service.
 */

namespace App\Service;

use App\Entity\Borrowing;
use App\Repository\BorrowingRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class BorrowingService.
 */
class BorrowingService
{
    /**
     * BorrowingService constructor.
     * @param BorrowingRepository $borrowingRepository
     * @param PaginatorInterface  $paginator
     */
    public function __construct(BorrowingRepository $borrowingRepository, PaginatorInterface $paginator)
    {
        $this->borrowingRepository = $borrowingRepository;
        $this->paginator = $paginator;
    }

    /**
     * Created paginated list.
     *
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function createdPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->borrowingRepository->queryAll(),
            $page,
            BorrowingRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save borrowing.
     *
     * @param Borrowing $borrowing
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Borrowing $borrowing): void
    {
        $this->borrowingRepository->save($borrowing);
    }

    /**
     * Delete borrowing.
     *
     * @param Borrowing $borrowing
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Borrowing $borrowing): void
    {
        $this->borrowingRepository->delete($borrowing);
    }
}
