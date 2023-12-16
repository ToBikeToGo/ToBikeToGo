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
    
                $scheduleShop = (new Schedule())
                    ->setDow($j)
                    ->addShop($this->getReference(ShopFixtures::SHOP_REFERENCE. $i))
                    ->setStartTime($startTime)
                    ->setEndTime($endTime);

                $manager->persist($scheduleShop);
            }

            $manager->persist($scheduleShop);
        }

        for ($i=0; $i < 40; $i++) { 
                for ($j=1; $j < 8; $j++) { 
                $startTime = $faker->dateTimeBetween("today 9:00", "today 17:00");
                $endTime = clone $startTime;
                $endTime->add(new \DateInterval('PT' . random_int(2, 8) . 'H'));
        
                $scheduleUser = (new Schedule())
                    ->setDow($j)
                    ->addUser($this->getReference(UserFixtures::EMPLOYEE_REFERENCE. $i))
                    ->setStartTime($startTime)
                    ->setEndTime($endTime)
                    ->setStartValidity(new \DateTime('now'));
        
                $manager->persist($scheduleUser);
            }
        
            $manager->persist($scheduleUser);
        }
        
        $manager->flush();

    }

    public function getDependencies(): array
    {
        return [
            ShopFixtures::class,
            UserFixtures::class,
        ];
    }
}
