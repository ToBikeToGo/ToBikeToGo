<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Bike;
use App\DataFixtures\ShopFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BikeFixtures extends Fixture implements DependentFixtureInterface
{
    public const BIKE_REFERENCE = 'bike-';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 30; $i++) {
            for ($j=0; $j < 5; $j++) {
                $object = (new Bike())
                    ->setLabel($faker->word())
                    ->setBrand($faker->company())
                    ->setPrice($faker->numberBetween(10, 100))
                    ->setShop($this->getReference(ShopFixtures::SHOP_REFERENCE. $i));

                    $manager->persist($object);

                    $this->addReference(self::BIKE_REFERENCE. $i * 5 + $j, $object);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ShopFixtures::class,
        ];
    }
}
