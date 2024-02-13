<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240209001522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE notification ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE notification ALTER updated_at DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('SELECT setval(\'notification_id_seq\', (SELECT MAX(id) FROM notification))');
        $this->addSql('ALTER TABLE notification ALTER id SET DEFAULT nextval(\'notification_id_seq\')');
        $this->addSql('ALTER TABLE notification ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE notification ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
    }
}
