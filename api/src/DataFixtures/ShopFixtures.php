<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Shop;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\FranchiseFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
;

class ShopFixtures extends Fixture implements DependentFixtureInterface
{
    public const SHOP_REFERENCE = 'shop-';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 20; $i++) { 
            $object = (new Shop())
                ->setLabel($faker->streetName())
                ->setAddress($faker->address())
                ->setIsOpened($faker->boolean(80))
                ->setFranchise($this->getReference(FranchiseFixtures::FRANCHISE_REFERENCE . $i))
                ->setCreatedBy($this->getReference(UserFixtures::PROVIDER_REFERENCE . $i))
                ->setUpdatedBy($this->getReference(UserFixtures::PROVIDER_REFERENCE . $i));

            $manager->persist($object);

            $this->addReference(self::SHOP_REFERENCE . $i, $object);
        }

        for ($i=20; $i < 30; $i++) { 
            $random = $faker->numberBetween(0, 19);

            $object = (new Shop())
                ->setLabel($faker->word())
                ->setAddress($faker->address())
                ->setIsOpened($faker->boolean(80))
                ->setFranchise($this->getReference(FranchiseFixtures::FRANCHISE_REFERENCE . $random))
                ->setCreatedBy($this->getReference(UserFixtures::PROVIDER_REFERENCE. $random))
                ->setUpdatedBy($this->getReference(UserFixtures::PROVIDER_REFERENCE. $random));

            $manager->persist($object);

            $this->addReference(self::SHOP_REFERENCE. $i, $object);
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
