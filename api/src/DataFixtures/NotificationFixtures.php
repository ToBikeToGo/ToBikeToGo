<?php

namespace App\DataFixtures;

use App\DataFixtures\UserFixtures;
use App\Entity\NotificationType;
use App\Repository\NotificationTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use App\Entity\Notification;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class NotificationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 30; $i++) {
            // Choisir aléatoirement un type de notification parmi ceux récupérés
            $notification = new Notification();
            $notification->setNotificationType($this->getReference(NotificationTypeFixtures::NOTIFICATION_TYPE_REFERENCE . rand(1,10)));
            $notification->addUser($this->getReference(UserFixtures::USER_REFERENCE . $i));
            $notification->setSender($this->getReference(UserFixtures::USER_REFERENCE . $i));
            $notification->setText($faker->text);
            $notification->setIsAlreadySeen(false);
            $manager->persist($notification);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            NotificationTypeFixtures::class,
        ];
    }
}
