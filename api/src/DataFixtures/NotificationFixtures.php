<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Notification;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class NotificationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 30; $i++) {
            $notification = (new Notification())
                ->addUser($this->getReference(UserFixtures::USER_REFERENCE . $i))
                ->setSender($this->getReference(UserFixtures::USER_REFERENCE . $i))
                ->setNotificationType(1);

            $manager->persist($notification);
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
