<?php

namespace App\DataFixtures;

use App\Entity\TypeCategory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TypeCategoryFixtures extends Fixture
{
    public const TYPE_CATEGORY = [
        'Roues',
        'Cadres',
        'Electriques',
    ];

    public function load(ObjectManager $manager): void
    {
        $object = (new TypeCategory())->setLabel('Roues');
        $manager->persist($object);

        $this->addReference(self::TYPE_CATEGORY[0], $object);

        $object = (new TypeCategory())->setLabel('Cadres');
        $manager->persist($object);

        $this->addReference(self::TYPE_CATEGORY[1], $object);

        $object = (new TypeCategory())->setLabel('Electriques');
        $manager->persist($object);

        $this->addReference(self::TYPE_CATEGORY[2], $object);

        $manager->flush();
    }
}
