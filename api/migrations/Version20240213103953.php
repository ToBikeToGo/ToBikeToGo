<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240213103953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
        INSERT INTO notification_type(id, designation, slug) VALUES
        (1, 'Ajout d’un nouvelle employé', 'employee_added'),
        (2, 'Nouvelle réservation d’un client', 'reservation_created'),
        (3, 'Modification de la réservation d’un client', 'reservation_modified'),
        (4, 'Annulation de la réservation d’un client', 'reservation_cancelled'),
        (5, 'Demande de création de compte', 'account_creation_request'),
        (6, 'Demande de congé', 'leave_request'),
        (7, 'Annulation de congé', 'leave_cancelled'),
        (8, 'Acceptation de congé', 'leave_accepted'),
        (9, 'Rappelle d’une reservation -2 jours', 'reservation_reminder'),
        (10, 'Modification d’une demande de congé', 'leave_modified')
    ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
    }
}
