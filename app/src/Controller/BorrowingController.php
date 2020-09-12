<?php
/**
 * Borrowing controller.
 */

namespace App\Controller;

use App\Entity\Borrowing;
use App\Form\BorrowingType;
use App\Service\BookService;
use App\Service\BorrowingService;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BorrowingController.
 *
 * @Route("/borrowing")
 */
class BorrowingController extends AbstractController
{
    /**
     * Book service.
     *
     * @var BookService
     */
    private $bookService;
    /**
     * Borrowing service.
     *
     * @var BorrowingService
     */
    private $borrowingService;

    /**
     * BorrowingController constructor.
     * @param BookService      $bookService
     * @param BorrowingService $borrowingService
     */
    public function __construct(BookService $bookService, BorrowingService $borrowingService)
    {
        $this->bookService = $bookService;
        $this->borrowingService = $borrowingService;
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="borrowing_create",
     * )
     */
    public function create(Request $request): Response
    {
        $borrowing = new Borrowing();
        $form = $this->createForm(BorrowingType::class, $borrowing, ['show_all_books' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->get('book')->getData();

            $book->setAmount($book->getAmount() - 1);
            // $borrowing->setAuthor($this->getUser());
            $borrowing->setCreatedAt(new DateTime());
            $borrowing->setIsExecuted(false);
            $this->bookService->save($book);
            $this->borrowingService->save($borrowing);

            $this->addFlash('success', 'message_borrowing_send');

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'borrowing/create.html.twig',
            [
                'form' => $form->createView(),
                'borrowing' => $borrowing,
            ]
        );
    }

    /**
     * Accept borrowing function.
     *
     * @param Request   $request
     * @param Borrowing $borrowing
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     * "/{id}/accept",
     * methods={"GET", "PUT"},
     * name="borrowing_accept",
     * requirements={"id": "[1-9]\d*"}
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function accept(Request $request, Borrowing $borrowing): Response
    {
        $form = $this->createForm(BorrowingType::class, $borrowing, ['method' => 'PUT', 'show_all_books' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $borrowing->setIsExecuted(true);
            $this->borrowingService->save($borrowing);
            $this->addFlash('success', 'message_borrowing_accept');

            return $this->redirectToRoute('manage_borrowing');
        }

        return $this->render(
            'borrowing/accept.html.twig',
            [
                'form' => $form->createView(),
                'borrowing' => $borrowing,
            ]
        );
    }

    /**
     * Decline borrowing.
     *
     * @param Request   $request
     * @param Borrowing $borrowing
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     * "/{id}/decline",
     * methods={"GET", "DELETE"},
     * name="borrowing_decline",
     * requirements={"id": "[1-9]\d*"}
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function decline(Request $request, Borrowing $borrowing): Response
    {
        $form = $this->createForm(BorrowingType::class, $borrowing, ['method' => 'DELETE', 'show_all_books' => true]);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $borrowing->setIsExecuted(true);
            $book = $form->get('book')->getData();
            $book->setAmount($book->getAmount() + 1);
            $this->bookService->save($book);
            $this->borrowingService->delete($borrowing);

            $this->addFlash('success', 'message_borrowing_decline');

            return $this->redirectToRoute('manage_borrowing');
        }

        return $this->render(
            'borrowing/decline.html.twig',
            [
                'form' => $form->createView(),
                'borrowing' => $borrowing,
            ]
        );
    }

    /**
     * Manage borrowing action.
     *
     * @Route(
     *     "/manage",
     *     name="manage_borrowing",
     *     methods={"GET", "POST"},
     *
     * )
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function manageBorrowing(Request $request): Response
    {
        $page = $request->query->getInt('page', '1');
        $pagination = $this->borrowingService->createdPaginatedList($page);

        return $this->render(
            'borrowing/manage.html.twig',
            ['pagination' => $pagination]
        );
    }
}
