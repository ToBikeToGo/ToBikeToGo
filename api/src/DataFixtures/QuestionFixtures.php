<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Question;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\TypeQuestionFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class QuestionFixtures extends Fixture implements DependentFixtureInterface
{
    public const QUESTION_REFERENCE = 'QUESTION-';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 20; $i++) {
            for ($j=0; $j < 3; $j++) {
                $object = (new Question())
                    ->setLabel($faker->sentence())
                    ->setTypeQuestion($this->getReference($faker->randomElement(TypeQuestionFixtures::TYPE_QUESTION)))
                    ->addCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE . $i));

                $manager->persist($object);

                $this->addReference(self::QUESTION_REFERENCE . ($i * 3 + $j), $object);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TypeQuestionFixtures::class,
        ];
    }
}
