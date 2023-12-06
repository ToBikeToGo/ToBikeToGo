<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231206140100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shop_schedule (shop_id INT NOT NULL, schedule_id INT NOT NULL, PRIMARY KEY(shop_id, schedule_id))');
        $this->addSql('CREATE INDEX IDX_8180EB6D4D16C4DD ON shop_schedule (shop_id)');
        $this->addSql('CREATE INDEX IDX_8180EB6DA40BC2D5 ON shop_schedule (schedule_id)');
        $this->addSql('ALTER TABLE shop_schedule ADD CONSTRAINT FK_8180EB6D4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shop_schedule ADD CONSTRAINT FK_8180EB6DA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule_shop DROP CONSTRAINT fk_4c226cf9a40bc2d5');
        $this->addSql('ALTER TABLE schedule_shop DROP CONSTRAINT fk_4c226cf94d16c4dd');
        $this->addSql('DROP TABLE schedule_shop');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE schedule_shop (schedule_id INT NOT NULL, shop_id INT NOT NULL, PRIMARY KEY(schedule_id, shop_id))');
        $this->addSql('CREATE INDEX idx_4c226cf94d16c4dd ON schedule_shop (shop_id)');
        $this->addSql('CREATE INDEX idx_4c226cf9a40bc2d5 ON schedule_shop (schedule_id)');
        $this->addSql('ALTER TABLE schedule_shop ADD CONSTRAINT fk_4c226cf9a40bc2d5 FOREIGN KEY (schedule_id) REFERENCES schedule (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule_shop ADD CONSTRAINT fk_4c226cf94d16c4dd FOREIGN KEY (shop_id) REFERENCES shop (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shop_schedule DROP CONSTRAINT FK_8180EB6D4D16C4DD');
        $this->addSql('ALTER TABLE shop_schedule DROP CONSTRAINT FK_8180EB6DA40BC2D5');
        $this->addSql('DROP TABLE shop_schedule');
    }
}
