<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Auth\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    public const PROVIDER_REFERENCE = 'provider-';
    public const EMPLOYEE_REFERENCE = 'employee-';
    public const USER_REFERENCE = 'user-';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $this->loadAdminUser($manager);
        $this->loadRegularUsers($manager, $faker);
        $this->loadProviderUsers($manager, $faker);
        $this->loadEmployeeUsers($manager, $faker);

        $manager->flush();
    }

    private function loadAdminUser(ObjectManager $manager): void
    {
        $user = (new User())
            ->setEmail('admin@admin.fr')
            ->setPassword('$2y$13$Pk4MfxwtvP7zZlHvH57WxeE5rZRdqAtp0kbWx/fXPiEd1lUiaN24u')
            ->setRoles(['ROLE_ADMIN'])
            ->setFirstName('Admin')
            ->setLastName('Admin')
            ->setPhone('0612345678')
            ->setStatus(true)
            ->setlocale('fr')
            ->setUpdatedAt(new \DateTime())
            ->setCreatedAt(new \DateTime());

        $manager->persist($user);
    }

    private function loadRegularUsers(ObjectManager $manager, Generator $faker): void
    {
        for ($i = 0; $i < 75; $i++) {
            $object = (new User())
                ->setEmail('user' . $i . '@user.fr')
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setPhone($faker->phoneNumber())
                ->setStatus($faker->boolean(80))
                ->setRoles(["ROLE_USER"])
                ->setPassword('$2y$13$Pk4MfxwtvP7zZlHvH57WxeE5rZRdqAtp0kbWx/fXPiEd1lUiaN24u')
                ->setlocale('fr');

            $manager->persist($object);

            $this->addReference(self::USER_REFERENCE . $i, $object);
        }
    }

    private function loadProviderUsers(ObjectManager $manager, Generator $faker): void
    {
        for ($i = 0; $i < 22; $i++) {
            $object = (new User())
                ->setEmail($faker->email())
                ->setPassword('$2y$13$Pk4MfxwtvP7zZlHvH57WxeE5rZRdqAtp0kbWx/fXPiEd1lUiaN24u')
                ->setRoles(['ROLE_PROVIDER'])
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setPhone($faker->phoneNumber())
                ->setStatus($faker->boolean(80))
                ->setlocale('fr')
                ->setUpdatedAt(new \DateTime())
                ->setCreatedAt(new \DateTime());

            $manager->persist($object);

            $this->addReference(self::PROVIDER_REFERENCE . $i, $object);
        }
    }

    private function loadEmployeeUsers(ObjectManager $manager, Generator $faker): void
    {
        for ($i = 0; $i < 41; $i++) {
            $object = (new User())
                ->setEmail($faker->email())
                ->setPassword('$2y$13$Pk4MfxwtvP7zZlHvH57WxeE5rZRdqAtp0kbWx/fXPiEd1lUiaN24u')
                ->setRoles(['ROLE_EMPLOYEE'])
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setPhone($faker->phoneNumber())
                ->setStatus($faker->boolean(80))
                ->setlocale('fr')
                ->setUpdatedAt(new \DateTime())
                ->setCreatedAt(new \DateTime());

            $manager->persist($object);

            $this->addReference(self::EMPLOYEE_REFERENCE . $i, $object);
        }
    }
}
