<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240129211919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE notification_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE notification_type (id INT NULL, designation VARCHAR(255) NOT NULL, slug VARCHAR(255) NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_34E21C13989D9B62 ON notification_type (slug)');
        $this->addSql('ALTER TABLE notification ADD notification_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE notification ADD sender_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification DROP notification_type');
        $this->addSql('ALTER TABLE notification DROP sender');
        $this->addSql('ALTER TABLE notification DROP sent_date');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAD0520624 FOREIGN KEY (notification_type_id) REFERENCES notification_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAF624B39D FOREIGN KEY (sender_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_BF5476CAD0520624 ON notification (notification_type_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CAF624B39D ON notification (sender_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CAD0520624');
        $this->addSql('DROP SEQUENCE notification_type_id_seq CASCADE');
        $this->addSql('DROP TABLE notification_type');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CAF624B39D');
        $this->addSql('DROP INDEX IDX_BF5476CAD0520624');
        $this->addSql('DROP INDEX IDX_BF5476CAF624B39D');
        $this->addSql('ALTER TABLE notification ADD notification_type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE notification ADD sender VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE notification ADD sent_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE notification DROP notification_type_id');
        $this->addSql('ALTER TABLE notification DROP sender_id');
        $this->addSql('ALTER TABLE vacation ALTER status TYPE BOOLEAN');
    }
}
