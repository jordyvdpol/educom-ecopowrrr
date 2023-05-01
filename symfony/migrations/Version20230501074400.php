<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230501074400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dummy_data (id INT AUTO_INCREMENT NOT NULL, klantnummer_id INT NOT NULL, message_id VARCHAR(10) NOT NULL, status VARCHAR(20) NOT NULL, date VARCHAR(100) NOT NULL, jaar INT NOT NULL, maand INT NOT NULL, total_yield DOUBLE PRECISION DEFAULT NULL, month_yield DOUBLE PRECISION DEFAULT NULL, total_surplus DOUBLE PRECISION DEFAULT NULL, month_surplus DOUBLE PRECISION DEFAULT NULL, INDEX IDX_3AE4FAC5EF1E6E76 (klantnummer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dummy_data ADD CONSTRAINT FK_3AE4FAC5EF1E6E76 FOREIGN KEY (klantnummer_id) REFERENCES klanten (id)');
        $this->addSql('ALTER TABLE klanten ADD stad VARCHAR(30) NOT NULL, ADD gemeente VARCHAR(100) NOT NULL, ADD provincie VARCHAR(100) NOT NULL, CHANGE voornaam voornaam VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dummy_data DROP FOREIGN KEY FK_3AE4FAC5EF1E6E76');
        $this->addSql('DROP TABLE dummy_data');
        $this->addSql('ALTER TABLE klanten DROP stad, DROP gemeente, DROP provincie, CHANGE voornaam voornaam VARCHAR(50) NOT NULL');
    }
}
