<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240205225613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE bike_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE bike_category (id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE bike ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bike ADD is_electric BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE bike ADD CONSTRAINT FK_4CBC378012469DE2 FOREIGN KEY (category_id) REFERENCES bike_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_4CBC378012469DE2 ON bike (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE bike DROP CONSTRAINT FK_4CBC378012469DE2');
        $this->addSql('DROP SEQUENCE bike_category_id_seq CASCADE');
        $this->addSql('DROP TABLE bike_category');
        $this->addSql('DROP INDEX IDX_4CBC378012469DE2');
        $this->addSql('ALTER TABLE bike DROP category_id');
        $this->addSql('ALTER TABLE bike DROP is_electric');
    }
}
