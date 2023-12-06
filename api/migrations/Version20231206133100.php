<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231206133100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_schedule (user_id INT NOT NULL, schedule_id INT NOT NULL, PRIMARY KEY(user_id, schedule_id))');
        $this->addSql('CREATE INDEX IDX_BAB5F5FBA76ED395 ON user_schedule (user_id)');
        $this->addSql('CREATE INDEX IDX_BAB5F5FBA40BC2D5 ON user_schedule (schedule_id)');
        $this->addSql('ALTER TABLE user_schedule ADD CONSTRAINT FK_BAB5F5FBA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_schedule ADD CONSTRAINT FK_BAB5F5FBA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_schedule DROP CONSTRAINT FK_BAB5F5FBA76ED395');
        $this->addSql('ALTER TABLE user_schedule DROP CONSTRAINT FK_BAB5F5FBA40BC2D5');
        $this->addSql('DROP TABLE user_schedule');
    }
}
