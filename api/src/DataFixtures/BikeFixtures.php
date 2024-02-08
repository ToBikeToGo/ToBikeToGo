<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Bike;
use App\DataFixtures\ShopFixtures;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\BikeCategoryFixtures;
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
                    ->setShop($this->getReference(ShopFixtures::SHOP_REFERENCE. $i))
                    ->setCategory($this->getReference(BikeCategoryFixtures::BIKE_CATEGORY_REFERENCE . rand(0, 23)))
                    ->setIsElectric($faker->boolean());

                    $manager->persist($object);

                    $this->addReference(self::BIKE_REFERENCE . ($i * 5 + $j), $object);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ShopFixtures::class,
            BikeCategoryFixtures::class
        ];
    }
}
