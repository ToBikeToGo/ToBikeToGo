<?php

namespace App\DataFixtures;

use App\Entity\BikeCategory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BikeCategoryFixtures extends Fixture
{
    public const BIKE_CATEGORY_REFERENCE = 'bike_category-';

    public function load(ObjectManager $manager): void
    {
        $categories = [
            ['City', 'Dutch'],
            ['City', 'Vintage'],
            ['City', 'Hybrid'],
            ['City', 'Folding'],
            ['City', 'Fixed Gear'],
            ['City', 'Tandem'],
            ['City', 'Cargo'],
            ['Road', 'MTB'],
            ['Road', 'Road'],
            ['Road', 'Gravel'],
            ['Road', 'Cyclocross'],
            ['Road', 'Endurance'],
            ['Road', 'Aero'],
            ['Road', 'Touring'],
            ['Road', 'Mountain'],
            ['Kids', 'City'],
            ['Recumbent', 'Two-wheeler'],
            ['Recumbent', 'Three-wheeler'],
            ['BMX', 'Race'],
            ['BMX', 'Flat'],
            ['BMX', 'Dirt'],
            ['BMX', 'Street'],
            ['BMX', 'Park'],
            ['BMX', 'Trail']
        ];

        foreach ($categories as $index => $category) {
            $object = (new BikeCategory())
                ->setName($category[0])
                ->setType($category[1]);

            $manager->persist($object);
            $this->addReference(self::BIKE_CATEGORY_REFERENCE . $index, $object);
        }

        $manager->flush();
    }
}
