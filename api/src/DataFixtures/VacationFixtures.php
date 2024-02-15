<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Vacation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

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
            $vacation->setShop($i > 3 ? $this->getReference('user.franchiseFranchiseShop') : $this->getReference(ShopFixtures::SHOP_REFERENCE. $i));
            $vacation->setStartDate($startDate);
            $vacation->setEndDate($endDate);
            $vacation->setStatus(random_int(0, 2));
            $vacation->setUser($this->getReference(UserFixtures::EMPLOYEE_REFERENCE . $i));

            $manager->persist($vacation);
        }

        for ($i=0; $i < 5; $i++) {
            $startDate = $faker->dateTimeBetween("today", "+2 month");
            $endDate = clone $startDate;
            $endDate->add(new \DateInterval('P' . random_int(1, 30). 'D'));

            $vacation = (new Vacation())
                ->setUser($this->getReference(UserFixtures::EMPLOYEE_REFERENCE . $i))
                ->setDescription($faker->text(200))
                ->setShop($i > 3 ? $this->getReference('user.franchiseFranchiseShop') : $this->getReference(ShopFixtures::SHOP_REFERENCE. $i))
                ->setStartDate($startDate)
                ->setEndDate($endDate)
                ->setStatus(random_int(0, 2));

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
