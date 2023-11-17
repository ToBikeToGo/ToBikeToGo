<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Request;
use App\DataFixtures\FranchiseFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
;

class RequestFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 10; $i++) {
            $request = (new Request())
                ->setFranchise($this->getReference(FranchiseFixtures::FRANCHISE_REFERENCE. $i))
                ->setRequestDate($faker->dateTimeBetween('-1 years', 'now'))
                ->setStatus($faker->randomElement(['pending', 'accepted', 'refused', 'canceled']));

            $manager->persist($request);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            FranchiseFixtures::class,
        ];
    }
}
