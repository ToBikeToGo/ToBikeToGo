<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240209185205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop ADD zip_code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE shop ADD city VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE shop RENAME COLUMN address TO street');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE shop ADD address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE shop DROP street');
        $this->addSql('ALTER TABLE shop DROP zip_code');
        $this->addSql('ALTER TABLE shop DROP city');
    }
}
