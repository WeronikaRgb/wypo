<?php
/**
 * Borrowing fixtures
 */
namespace App\DataFixtures;
use App\Entity\Borrowing;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
/**
 * Class BorrowingFixtures
 */
class  BorrowingFixtures extends  AbstractBaseFixtures implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'borrowings', function ($i) {
            $borrowing = new Borrowing();
            $borrowing->setBook($this->getRandomReference('books'));
            $borrowing->setComment($this->faker->colorName);
            $borrowing->setNick($this->faker->city);
            $borrowing->setEmail(sprintf('anonim%d@example.com', $i));
            $borrowing->setIsExecuted(false);

            return $borrowing;
        });
        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [BookFixtures::class];
    }


}