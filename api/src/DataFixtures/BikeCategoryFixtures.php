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
        $object = (new BikeCategory())
            ->setName('Ville')
            ->setType('Hollandais');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 0, $object);

        $object = (new BikeCategory())
            ->setName('Ville')
            ->setType('Vintage');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 1, $object);

        $object = (new BikeCategory())
            ->setName('Ville')
            ->setType('VTC');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 2, $object);

        $object = (new BikeCategory())
            ->setName('Ville')
            ->setType('Pliant');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 3, $object);

        $object = (new BikeCategory())
            ->setName('Ville')
            ->setType('Fixie');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 4, $object);

        $object = (new BikeCategory())
            ->setName('Ville')
            ->setType('Tandem');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 5, $object);

        $object = (new BikeCategory())
            ->setName('Ville')
            ->setType('Cargo');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 6, $object);

        $object = (new BikeCategory())
            ->setName('Route')
            ->setType('VTT');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 7, $object);

        $object = (new BikeCategory())
            ->setName('Route')
            ->setType('Route');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 8, $object);

        $object = (new BikeCategory())
            ->setName('Route')
            ->setType('Gravel');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 9, $object);

        $object = (new BikeCategory())
            ->setName('Route')
            ->setType('Cyclo-cross');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 10, $object);

        $object = (new BikeCategory())
            ->setName('Enfant')
            ->setType('Ville');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 11, $object);

        $object = (new BikeCategory())
            ->setName('Couché')
            ->setType('Deux roues');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 12, $object);

        $object = (new BikeCategory())
            ->setName('Couché')
            ->setType('Trois roues');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 13, $object);

        // BMX
        $object = (new BikeCategory())
            ->setName('BMX')
            ->setType('Race');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 14, $object);

        $object = (new BikeCategory())
            ->setName('BMX')
            ->setType('Flat');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 15, $object);

        $object = (new BikeCategory())
            ->setName('BMX')
            ->setType('Dirt');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 16, $object);

        $object = (new BikeCategory())
            ->setName('BMX')
            ->setType('Street');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 17, $object);

        $object = (new BikeCategory())
            ->setName('BMX')
            ->setType('Park');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 18, $object);

        $object = (new BikeCategory())
            ->setName('BMX')
            ->setType('Trail');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 19, $object);

        $object = (new BikeCategory())
            ->setName('Route')
            ->setType('Endurance');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 20, $object);

        $object = (new BikeCategory())
            ->setName('Route')
            ->setType('Aéro');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 21, $object);

        $object = (new BikeCategory())
            ->setName('Route')
            ->setType('Voyage');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 22, $object);

        $object = (new BikeCategory())
            ->setName('Route')
            ->setType('Montagne');

        $manager->persist($object);

        $this->addReference(self::BIKE_CATEGORY_REFERENCE . 23, $object);

        $manager->flush();
    }
}
