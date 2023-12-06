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

        $adresses = [
            '90 Bd Diderot, 75012 Paris',
            '1 Rue de la Pompe, 75116 Paris',
            '36 Rue du Viaduc, 89000 Auxerre',
            '1 Rue de la République, 89000 Auxerre',
            '20 Avenue des Champs-Élysées, 75008 Paris',
            '45 Rue de la Roquette, 75011 Paris',
            '15 Place Bellecour, 69002 Lyon',
            '8 Quai des Belges, 13001 Marseille',
            '25 Rue du Faubourg Saint-Honoré, 75008 Paris',
            '10 Rue de la Liberté, 59000 Lille',
            '5 Avenue Foch, 75116 Paris',
            '18 Rue de la Bourse, 75002 Paris',
            '3 Quai de la Tournelle, 75005 Paris',
            '50 Rue de la Convention, 75015 Paris',
            '12 Place des Terreaux, 69001 Lyon',
            '6 Rue Saint-Antoine, 75004 Paris',
            '2 Avenue Montaigne, 75008 Paris',
            '22 Rue de Rivoli, 75001 Paris',
            '7 Quai d\'Orléans, 75004 Paris',
            '30 Avenue Pierre 1er de Serbie, 75016 Paris',
            '11 Rue de la Paix, 75002 Paris',
            '4 Place de la Comédie, 34000 Montpellier',
            '16 Quai de la Tournelle, 75005 Paris',
            '40 Rue du Faubourg Saint-Denis, 75010 Paris',
            '9 Quai des Écluses, 75010 Paris',
            '17 Place Vendôme, 75001 Paris',
            '27 Rue Royale, 75008 Paris',
            '14 Rue du Cherche-Midi, 75006 Paris',
            '33 Quai des Grands Augustins, 75006 Paris',
            '19 Rue de la Paix, 75002 Paris',
            '26 Avenue Montaigne, 75008 Paris',
            '13 Rue de la Bûcherie, 75005 Paris',
            '32 Quai de la Tournelle, 75005 Paris',
            '21 Rue Cambon, 75001 Paris',
            '23 Quai de Conti, 75006 Paris',
            '31 Rue du Faubourg Saint-Honoré, 75008 Paris',
            '24 Rue de la Pompe, 75116 Paris',
            '35 Rue Saint-Honoré, 75001 Paris',
            '28 Rue de la Roquette, 75011 Paris',
            '16 Rue du Faubourg Saint-Honoré, 75008 Paris',
            '29 Quai de la Tournelle, 75005 Paris',
            '2 Rue de la Paix, 75002 Paris',
            '39 Avenue Montaigne, 75008 Paris',
            '10 Quai de la Tournelle, 75005 Paris',
            '18 Quai de la Tournelle, 75005 Paris',
            '37 Quai de la Tournelle, 75005 Paris',
            '12 Rue de la Paix, 75002 Paris',
        ];

        for ($i=0; $i < 20; $i++) { 
            $object = (new Shop())
                ->setLabel($faker->streetName())
                ->setAddress($adresses[$i])
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
                ->setAddress($adresses[$i])
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
