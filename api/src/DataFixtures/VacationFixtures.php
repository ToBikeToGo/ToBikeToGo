<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Vacation;
use App\DataFixtures\ShopFixtures;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
;

class VacationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 5; $i++) { 
            $startDate = $faker->dateTimeBetween("today", "+2 month");
            $endDate = clone $startDate;
            $endDate->add(new \DateInterval('P' . random_int(1, 30). 'D'));

            $vacation = new Vacation();
            $vacation->setShop($this->getReference(ShopFixtures::SHOP_REFERENCE . $i));
            $vacation->setStartDate($startDate);
            $vacation->setEndDate($endDate);
            $vacation->setStatus($faker->boolean(80));

            $manager->persist($vacation);
        }

        for ($i=0; $i < 5; $i++) { 
            $startDate = $faker->dateTimeBetween("today", "+2 month");
            $endDate = clone $startDate;
            $endDate->add(new \DateInterval('P' . random_int(1, 30). 'D'));

            $vacation = (new Vacation())
                ->setUser($this->getReference(UserFixtures::EMPLOYEE_REFERENCE . $i))
                ->setDescription($faker->text(200))
                ->setStartDate($startDate)
                ->setEndDate($endDate)
                ->setStatus($faker->boolean(80));

            $manager->persist($vacation);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ShopFixtures::class,
        ];
    }
}
