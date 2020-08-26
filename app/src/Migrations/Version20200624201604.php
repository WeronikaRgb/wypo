<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200624201604 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE books CHANGE amount amount INT DEFAULT NULL');
        $this->addSql('ALTER TABLE borrowings ADD status_id INT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL, ADD email VARCHAR(45) DEFAULT NULL, ADD nick VARCHAR(45) DEFAULT NULL, ADD comment VARCHAR(45) DEFAULT NULL, CHANGE book_id book_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE borrowings ADD CONSTRAINT FK_7547A7B46BF700BD FOREIGN KEY (status_id) REFERENCES borrowings_status (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7547A7B46BF700BD ON borrowings (status_id)');
        $this->addSql('ALTER TABLE users CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE books CHANGE amount amount INT DEFAULT NULL');
        $this->addSql('ALTER TABLE borrowings DROP FOREIGN KEY FK_7547A7B46BF700BD');
        $this->addSql('DROP INDEX UNIQ_7547A7B46BF700BD ON borrowings');
        $this->addSql('ALTER TABLE borrowings DROP status_id, DROP created_at, DROP email, DROP nick, DROP comment, CHANGE book_id book_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
