<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Category;
use App\Entity\TypeCategory;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\TypeCategoryFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CategoryFixtures extends Fixture implements DependentFixtureInterface
{
    public const CATEGORY_REFERENCE = 'CATEGORY-';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 20; $i++) {
            $object = (new Category())
                ->setLabel($faker->sentence())
                ->setTypeCategory($this->getReference($faker->randomElement(TypeCategoryFixtures::TYPE_CATEGORY)));

            $manager->persist($object);

            $this->addReference(self::CATEGORY_REFERENCE . $i, $object);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TypeCategoryFixtures::class,
        ];
    }
}
