<?php

namespace App\DataFixtures;

use App\Entity\NotificationType;
use App\Repository\NotificationTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use App\Entity\Notification;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class NotificationTypeFixtures extends Fixture implements DependentFixtureInterface
{

    public const NOTIFICATION_TYPE_REFERENCE = 'notification-type-';

    public function load(ObjectManager $manager): void
    {
        $typesData = [
            [
                'id' => 1,
                'designation' => 'Ajout d’un nouvelle employé',
                'slug' => 'employee_added'
            ],
            [
                'id' => 2,
                'designation' => 'Nouvelle réservation d’un client',
                'slug' => 'reservation_created'
            ],
            [
                'id' => 3,
                'designation' => 'Modification de la réservation d’un client',
                'slug' => 'reservation_modified'
            ],
            [
                'id' => 4,
                'designation' => 'Annulation de la réservation d’un client',
                'slug' => 'reservation_cancelled'
            ],
            [
                'id' => 5,
                'designation' => 'Demande de création de compte',
                'slug' => 'account_creation_request'
            ],
            [
                'id' => 6,
                'designation' => 'Demande de congé',
                'slug' => 'leave_request'
            ],
            [
                'id' => 7,
                'designation' => 'Annulation de congé',
                'slug' => 'leave_cancelled'
            ],
            [
                'id' => 8,
                'designation' => 'Acceptation de congé',
                'slug' => 'leave_accepted'
            ],
            [
                'id' => 9,
                'designation' => 'Rappelle d’une reservation -2 jours',
                'slug' => 'reservation_reminder'
            ],
            [
                'id' => 10,
                'designation' => 'Modification d’une demande de congé',
                'slug' => 'leave_modified'
            ],
        ];

        foreach ($typesData as $typeData) {
            $notificationType = new NotificationType();
            $notificationType->setDesignation($typeData['designation']);
            $notificationType->setSlug($typeData['slug']);

            $manager->persist($notificationType);
            $this->addReference(self::NOTIFICATION_TYPE_REFERENCE . $typeData['id'], $notificationType);

        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
