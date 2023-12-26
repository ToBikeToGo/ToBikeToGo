<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231202133221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_shop (user_id INT NOT NULL, shop_id INT NOT NULL, PRIMARY KEY(user_id, shop_id))');
        $this->addSql('CREATE INDEX IDX_D6EB006BA76ED395 ON user_shop (user_id)');
        $this->addSql('CREATE INDEX IDX_D6EB006B4D16C4DD ON user_shop (shop_id)');
        $this->addSql('ALTER TABLE user_shop ADD CONSTRAINT FK_D6EB006BA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_shop ADD CONSTRAINT FK_D6EB006B4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_shop DROP CONSTRAINT FK_D6EB006BA76ED395');
        $this->addSql('ALTER TABLE user_shop DROP CONSTRAINT FK_D6EB006B4D16C4DD');
        $this->addSql('DROP TABLE user_shop');
    }
}
