<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Shop;
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

        $addresses = [
            ['street' => '90 Bd Diderot', 'zip_code' => '75012', 'city' => 'Paris'],
            ['street' => '1 Rue de la Pompe', 'zip_code' => '75116', 'city' => 'Paris'],
            ['street' => '36 Rue du Viaduc', 'zip_code' => '89000', 'city' => 'Auxerre'],
            ['street' => '1 Rue de la République', 'zip_code' => '89000', 'city' => 'Auxerre'],
            ['street' => '20 Avenue des Champs-Élysées', 'zip_code' => '75008', 'city' => 'Paris'],
            ['street' => '45 Rue de la Roquette', 'zip_code' => '75011', 'city' => 'Paris'],
            ['street' => '15 Place Bellecour', 'zip_code' => '69002', 'city' => 'Lyon'],
            ['street' => '8 Quai des Belges', 'zip_code' => '13001', 'city' => 'Marseille'],
            ['street' => '25 Rue du Faubourg Saint-Honoré', 'zip_code' => '75008', 'city' => 'Paris'],
            ['street' => '10 Rue de la Liberté', 'zip_code' => '59000', 'city' => 'Lille'],
            ['street' => '5 Avenue Foch', 'zip_code' => '75116', 'city' => 'Paris'],
            ['street' => '18 Rue de la Bourse', 'zip_code' => '75002', 'city' => 'Paris'],
            ['street' => '3 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris'],
            ['street' => '50 Rue de la Convention', 'zip_code' => '75015', 'city' => 'Paris'],
            ['street' => '12 Place des Terreaux', 'zip_code' => '69001', 'city' => 'Lyon'],
            ['street' => '6 Rue Saint-Antoine', 'zip_code' => '75004', 'city' => 'Paris'],
            ['street' => '2 Avenue Montaigne', 'zip_code' => '75008', 'city' => 'Paris'],
            ['street' => '22 Rue de Rivoli', 'zip_code' => '75001', 'city' => 'Paris'],
            ['street' => '7 Quai d\'Orléans', 'zip_code' => '75004', 'city' => 'Paris'],
            ['street' => '30 Avenue Pierre 1er de Serbie', 'zip_code' => '75016', 'city' => 'Paris'],
            ['street' => '11 Rue de la Paix', 'zip_code' => '75002', 'city' => 'Paris'],
            ['street' => '4 Place de la Comédie', 'zip_code' => '34000', 'city' => 'Montpellier'],
            ['street' => '16 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris'],
            ['street' => '40 Rue du Faubourg Saint-Denis', 'zip_code' => '75010', 'city' => 'Paris'],
            ['street' => '9 Quai des Écluses', 'zip_code' => '75010', 'city' => 'Paris'],
            ['street' => '17 Place Vendôme', 'zip_code' => '75001', 'city' => 'Paris'],
            ['street' => '27 Rue Royale', 'zip_code' => '75008', 'city' => 'Paris'],
            ['street' => '14 Rue du Cherche-Midi', 'zip_code' => '75006', 'city' => 'Paris'],
            ['street' => '33 Quai des Grands Augustins', 'zip_code' => '75006', 'city' => 'Paris'],
            ['street' => '19 Rue de la Paix', 'zip_code' => '75002', 'city' => 'Paris'],
            ['street' => '26 Avenue Montaigne', 'zip_code' => '75008', 'city' => 'Paris'],
            ['street' => '13 Rue de la Bûcherie', 'zip_code' => '75005', 'city' => 'Paris'],
            ['street' => '32 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris'],
            ['street' => '21 Rue Cambon', 'zip_code' => '75001', 'city' => 'Paris'],
            ['street' => '23 Quai de Conti', 'zip_code' => '75006', 'city' => 'Paris'],
            ['street' => '31 Rue du Faubourg Saint-Honoré', 'zip_code' => '75008', 'city' => 'Paris'],
            ['street' => '24 Rue de la Pompe', 'zip_code' => '75116', 'city' => 'Paris'],
            ['street' => '35 Rue Saint-Honoré', 'zip_code' => '75001', 'city' => 'Paris'],
            ['street' => '28 Rue de la Roquette', 'zip_code' => '75011', 'city' => 'Paris'],
            ['street' => '16 Rue du Faubourg Saint-Honoré', 'zip_code' => '75008', 'city' => 'Paris'],
            ['street' => '29 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris'],
            ['street' => '2 Rue de la Paix', 'zip_code' => '75002', 'city' => 'Paris'],
            ['street' => '39 Avenue Montaigne', 'zip_code' => '75008', 'city' => 'Paris'],
            ['street' => '10 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris'],
            ['street' => '18 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris'],
            ['street' => '37 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris'],
            ['street' => '12 Rue de la Paix', 'zip_code' => '75002', 'city' => 'Paris'],
        ];

        for ($i=0; $i < 21; $i++) {
            $userIndex = $i % 136;
            $object = (new Shop())
                ->setLabel($faker->streetName())
                ->setStreet($addresses[$i]['street'])
                ->setZipCode($addresses[$i]['zip_code'])
                ->setCity($addresses[$i]['city'])
                ->setIsOpened($faker->boolean(80))
                ->setFranchise($this->getReference(FranchiseFixtures::FRANCHISE_REFERENCE . $i))
                ->setCreatedBy($this->getReference(UserFixtures::PROVIDER_REFERENCE . $userIndex))
                ->setUpdatedBy($this->getReference(UserFixtures::PROVIDER_REFERENCE . $userIndex))
                ->addUsers($this->getReference(UserFixtures::PROVIDER_REFERENCE . $userIndex));

            $manager->persist($object);

            $this->addReference(self::SHOP_REFERENCE . $i, $object);
        }

        for ($i=21; $i < 31; $i++) {
            $random = $faker->numberBetween(0, 19);

            $object = (new Shop())
                ->setLabel($faker->word())
                ->setStreet($addresses[$i]['street'])
                ->setZipCode($addresses[$i]['zip_code'])
                ->setCity($addresses[$i]['city'])
                ->setIsOpened($faker->boolean(80))
                ->setFranchise($this->getReference(FranchiseFixtures::FRANCHISE_REFERENCE . $random))
                ->setCreatedBy($this->getReference(UserFixtures::PROVIDER_REFERENCE. $random))
                ->setUpdatedBy($this->getReference(UserFixtures::PROVIDER_REFERENCE. $random))
                ->addUsers($this->getReference(UserFixtures::PROVIDER_REFERENCE . $random));

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
