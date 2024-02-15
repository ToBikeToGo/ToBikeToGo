<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Franchise;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FranchiseFixtures extends Fixture implements DependentFixtureInterface
{
    public const FRANCHISE_REFERENCE = 'franchise-';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 20; $i++) {
            $object = (new Franchise())
                ->setLabel($faker->company())
                ->setIsActive($faker->boolean(80))
                ->addUser($this->getReference(UserFixtures::PROVIDER_REFERENCE . $i))
                ->setCreatedBy($this->getReference(UserFixtures::PROVIDER_REFERENCE . $i))
                ->setUpdatedBy($this->getReference(UserFixtures::PROVIDER_REFERENCE . $i));

                for ($j=0; $j < 2; $j++) {
                    $object->addUser($this->getReference(UserFixtures::EMPLOYEE_REFERENCE . ($i * 2 + $j)));
                }

            $manager->persist($object);

            $this->addReference(self::FRANCHISE_REFERENCE . $i, $object);
        }

        $object = (new Franchise())
            ->setLabel($faker->company())
            ->setIsActive($faker->boolean(80))
            ->addUser($this->getReference(UserFixtures::PROVIDER_REFERENCE . 20))
            ->setCreatedBy($this->getReference(UserFixtures::PROVIDER_REFERENCE . 20))
            ->setUpdatedBy($this->getReference(UserFixtures::PROVIDER_REFERENCE . 20));

        $manager->persist($object);

        $this->addReference(self::FRANCHISE_REFERENCE . 20, $object);

        $manager->flush();

        $userFranchise = (new Franchise())
            ->setLabel($faker->company())
            ->setIsActive(true)
            ->addUser($this->getReference('user.franchise'))
            ->setCreatedBy($this->getReference('user.franchise'))
            ->setUpdatedBy($this->getReference('user.franchise'));


        $this->addReference('user.franchiseFranchise', $userFranchise);

        $manager->persist($userFranchise);
        $manager->flush();



    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
