<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Schedule;
use App\DataFixtures\ShopFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
;

class ScheduleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 30; $i++) { 
            for ($j=1; $j < 8; $j++) { 
                $startTime = $faker->dateTimeBetween("today 7:00", "today 12:00");
                $endTime = clone $startTime;
                $endTime->add(new \DateInterval('PT' . random_int(2, 8) . 'H'));
    
                $schedule = (new Schedule())
                    ->setDow($j)
                    ->addShop($this->getReference(ShopFixtures::SHOP_REFERENCE. $i))
                    ->setStartTime($startTime)
                    ->setEndTime($endTime);

                $manager->persist($schedule);
            }

            $manager->persist($schedule);
        }

        $manager->flush();
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ShopFixtures::class,
        ];
    }
}
