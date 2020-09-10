<?php

declare(strict_types=1);

/**
 * Migration.
 */
namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200816181223 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription() : string
    {
        return '';
    }

    /**
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE borrowings DROP FOREIGN KEY FK_7547A7B46BF700BD');
        $this->addSql('DROP TABLE borrowings_status');
        $this->addSql('ALTER TABLE books ADD created_at DATETIME NOT NULL, CHANGE amount amount INT DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_7547A7B46BF700BD ON borrowings');
        $this->addSql('ALTER TABLE borrowings ADD is_executed TINYINT(1) DEFAULT NULL, DROP status_id, CHANGE book_id book_id INT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE email email VARCHAR(45) DEFAULT NULL, CHANGE nick nick VARCHAR(45) DEFAULT NULL, CHANGE comment comment VARCHAR(45) DEFAULT NULL');
        $this->addSql('ALTER TABLE users CHANGE roles roles JSON NOT NULL');
    }

    /**
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE borrowings_status (id INT AUTO_INCREMENT NOT NULL, is_executed TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE books DROP created_at, CHANGE amount amount INT DEFAULT NULL');
        $this->addSql('ALTER TABLE borrowings ADD status_id INT DEFAULT NULL, DROP is_executed, CHANGE book_id book_id INT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT \'NULL\', CHANGE email email VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE nick nick VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE comment comment VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE borrowings ADD CONSTRAINT FK_7547A7B46BF700BD FOREIGN KEY (status_id) REFERENCES borrowings_status (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7547A7B46BF700BD ON borrowings (status_id)');
        $this->addSql('ALTER TABLE users CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
