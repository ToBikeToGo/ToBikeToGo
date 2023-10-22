<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\DataFixtures\BookingFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
;

class PaymentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 20; $i++) { 
            $object = (new \App\Entity\Payment())
                ->setPrice($faker->numberBetween(10, 1000))
                ->setCommission($faker->numberBetween(1, 20))
                ->setStripeId($faker->randomDigit())
                ->setStatus($faker->boolean(80))
                ->setPaymentDate($this->getReference(BookingFixtures::BOOKING_REFERENCE. $i)->getEndDate())
                ->setBooking($this->getReference(BookingFixtures::BOOKING_REFERENCE. $i))
                ->addUser($this->getReference(UserFixtures::USER_REFERENCE. $i))
                ->addShop($this->getReference(ShopFixtures::SHOP_REFERENCE. $i));

            $manager->persist($object);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [BookingFixtures::class];
    }
}
