<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Booking;
use App\DataFixtures\BikeFixtures;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
;

class BookingFixtures extends Fixture implements DependentFixtureInterface
{
    public const BOOKING_REFERENCE = 'booking-';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Make a booking for a bike each two
        for ($i=0; $i < 75; $i++) { 
            $startDate = $faker->dateTimeBetween("today", "+1 month");
            $endDate = clone $startDate;
            $endDate->add(new \DateInterval('P' . random_int(1, 30) . 'D'));

            $booking = (new Booking())
            ->setBike($this->getReference(BikeFixtures::BIKE_REFERENCE. $i))
            ->setUser($this->getReference(UserFixtures::USER_REFERENCE. $i))
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setRating($faker->randomFloat(1, 0, 5))
            ->setStatus($faker->boolean(80));

            $manager->persist($booking);

            $this->addReference(self::BOOKING_REFERENCE. $i, $booking);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            BikeFixtures::class,
        ];
    }
}
