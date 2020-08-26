<?php
/**
 * Book service.
 */

namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class BookService.
 */
class BookService
{
    /**
     * Book repository.
     *
     * @var \App\Repository\BookRepository
     */
    private $bookRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * BookService constructor.
     *
     * @param \App\Repository\BookRepository          $bookRepository Book repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator      Paginator
     */
    public function __construct(BookRepository $bookRepository, PaginatorInterface $paginator)
    {
        $this->bookRepository = $bookRepository;
        $this->paginator = $paginator;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->bookRepository->queryAll(),
            $page,
            BookRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Query builder for all books.
     *
     * @return QueryBuilder
     */
    public function getAllBooks(): QueryBuilder
    {
        return $this->bookRepository->getAllBooks();
    }

    /**
     * Query builder for only available books.
     *
     * @return QueryBuilder
     */
    public function getAvailableBooks(): QueryBuilder
    {
        return $this->bookRepository->getAvailableBooks();
    }

    /**
     * Save book.
     *
     * @param Book $book
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Book $book): void
    {
        $this->bookRepository->save($book);
    }

    /**
     * Delete book.
     *
     * @param Book $book
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Book $book): void
    {
        $this->bookRepository->delete($book);
    }
}
