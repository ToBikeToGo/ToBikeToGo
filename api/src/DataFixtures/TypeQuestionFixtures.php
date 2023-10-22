<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\TypeQuestion;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
;

class TypeQuestionFixtures extends Fixture
{
    public const TYPE_QUESTION = [
        'QCM',
        'SLIDER',
        'RADIO',
        'CHECKBOX',
        'TEXT',
        'DATE',
        'TIME',
        'DATETIME',
        'IMAGE',
        'VIDEO',
    ];
    public const TYPE_QUESTION_OTHER = 'OTHER';


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        foreach (self::TYPE_QUESTION as $typeQuestion) {
            $object = new TypeQuestion();
            $object->setLabel($typeQuestion);

            $manager->persist($object);

            $this->addReference($typeQuestion, $object);
        }

        $object = new TypeQuestion();
        $object->setLabel('OTHER');
        $object->setPlaceholder($faker->sentence());

        $manager->persist($object);

        $this->addReference(self::TYPE_QUESTION_OTHER, $object);

        $manager->flush();
    }
}
