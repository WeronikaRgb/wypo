<?php
/**
 * Borrowing form.
 */

namespace App\Form;

use App\Entity\Book;
use App\Service\BookService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BorrowingType.
 */
class BorrowingType extends AbstractType
{
    /**
     * Book service.
     *
     * @var BookService
     */
    private $bookService;

    /**
     * BorrowingType constructor.
     *
     * @param BookService $bookService
     */
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'book',
            EntityType::class,
            [
                'class' => Book::class,
                'choice_label' => 'title',
                'query_builder' => $options['show_all_books'] ? $this->bookService->getAllBooks() : $this->bookService->getAvailableBooks(),
                'required' => true,
                'placeholder' => 'choice_book',
                'label' => 'label_books',
            ]
        );

        $builder->add(
            'nick',
            TextType::class,
            [
                'label' => 'label_nick',
                'required' => true,
            ]
        );

        $builder->add(
            'email',
            EmailType::class,
            [
                'label' => 'label_email',
                'required' => true,
            ]
        );

        $builder->add(
            'comment',
            TextType::class,
            [
                'label' => 'label_comment',
                'required' => true,
                'attr' => ['max_length' => 45],
            ]
        );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([BorrowingType::class]);
        $resolver->setRequired('show_all_books');
        $resolver->setAllowedTypes('show_all_books', 'bool');
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'borrowing';
    }
}
