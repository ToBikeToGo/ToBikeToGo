<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Proposition;
use App\DataFixtures\BikeFixtures;
use App\DataFixtures\QuestionFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PropositionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 20; $i++) { 
            for ($j=0; $j < 3; $j++) { 
                $object = (new Proposition())
                    ->setLabel($faker->sentence())
                    ->setQuestion($this->getReference(QuestionFixtures::QUESTION_REFERENCE . $i * 3 + $j))
                    ->addBike($this->getReference(BikeFixtures::BIKE_REFERENCE . $i * 5 + $j));

                $manager->persist($object);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            QuestionFixtures::class,
            BikeFixtures::class,
        ];
    }
}
