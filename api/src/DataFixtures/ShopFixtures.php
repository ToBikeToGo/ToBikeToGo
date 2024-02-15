<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Shop;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

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
            ['street' => '12 Rue de la Paix', 'zip_code' => '75002', 'city' => 'Paris', 'latitude' => 48.8696, 'longitude' => 2.3301],
            ['street' => '38 Avenue Montaigne', 'zip_code' => '75008', 'city' => 'Paris', 'latitude' => 48.8675, 'longitude' => 2.3086],
            ['street' => '11 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris', 'latitude' => 48.8503, 'longitude' => 2.3543],
            ['street' => '17 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris', 'latitude' => 48.8503, 'longitude' => 2.3543],
            ['street' => '36 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris', 'latitude' => 48.8503, 'longitude' => 2.3543],
            ['street' => '25 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris', 'latitude' => 48.8503, 'longitude' => 2.3543],
            ['street' => '3 Rue de la Paix', 'zip_code' => '75002', 'city' => 'Paris', 'latitude' => 48.8696, 'longitude' => 2.3301],
            ['street' => '40 Avenue Montaigne', 'zip_code' => '75008', 'city' => 'Paris', 'latitude' => 48.8675, 'longitude' => 2.3086],
            ['street' => '13 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris', 'latitude' => 48.8503, 'longitude' => 2.3543],
            ['street' => '19 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris', 'latitude' => 48.8503, 'longitude' => 2.3543],
            ['street' => '26 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris', 'latitude' => 48.8503, 'longitude' => 2.3543],
            ['street' => '14 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris', 'latitude' => 48.8503, 'longitude' => 2.3543],
            ['street' => '4 Rue de la Paix', 'zip_code' => '75002', 'city' => 'Paris', 'latitude' => 48.8696, 'longitude' => 2.3301],
            ['street' => '41 Avenue Montaigne', 'zip_code' => '75008', 'city' => 'Paris', 'latitude' => 48.8675, 'longitude' => 2.3086],
            ['street' => '15 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris', 'latitude' => 48.8503, 'longitude' => 2.3543],
            ['street' => '20 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris', 'latitude' => 48.8503, 'longitude' => 2.3543],
            ['street' => '27 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris', 'latitude' => 48.8503, 'longitude' => 2.3543],
            ['street' => '16 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris', 'latitude' => 48.8503, 'longitude' => 2.3543],
            ['street' => '5 Rue de la Paix', 'zip_code' => '75002', 'city' => 'Paris', 'latitude' => 48.8696, 'longitude' => 2.3301],
            ['street' => '42 Avenue Montaigne', 'zip_code' => '75008', 'city' => 'Paris', 'latitude' => 48.8675, 'longitude' => 2.3086
            ],
            ['street' => '21 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris', 'latitude' => 48.8503, 'longitude' => 2.3543],
            ['street' => '28 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris', 'latitude' => 48.8503, 'longitude' => 2.3543],
            ['street' => '17 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris', 'latitude' => 48.8503, 'longitude' => 2.3543],
            ['street' => '6 Rue de la Paix', 'zip_code' => '75002', 'city' => 'Paris', 'latitude' => 48.8696, 'longitude' => 2.3301],
            ['street' => '43 Avenue Montaigne', 'zip_code' => '75008', 'city' => 'Paris', 'latitude' => 48.8675, 'longitude' => 2.3086],
            ['street' => '22 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris', 'latitude' => 48.8503, 'longitude' => 2.3543],
            ['street' => '29 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris', 'latitude' => 48.8503, 'longitude' => 2.3543],
            ['street' => '18 Quai de la Tournelle', 'zip_code' => '75005', 'city' => 'Paris', 'latitude' => 48.8503, 'longitude' => 2.3543],
            ['street' => '7 Rue de la Paix', 'zip_code' => '75002', 'city' => 'Paris', 'latitude' => 48.8696, 'longitude' => 2.3301]];

        for ($i=0; $i < 21; $i++) {
            $userIndex = $i % 136;
            $object = (new Shop())
                ->setLabel($faker->company())
                ->setStreet($addresses[$i]['street'])
                ->setZipCode($addresses[$i]['zip_code'])
                ->setCity($addresses[$i]['city'])
                ->setLatitude($addresses[$i]['latitude'] ?? null)
                ->setLongitude($addresses[$i]['longitude'] ?? null)
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
                ->setLatitude($addresses[$i]['latitude'] ?? null)
                ->setLongitude($addresses[$i]['longitude'] ?? null)
                ->addUsers($this->getReference(UserFixtures::PROVIDER_REFERENCE . $random));

            $manager->persist($object);

            $this->addReference(self::SHOP_REFERENCE. $i, $object);
        }




            $object = (new Shop())
                ->setLabel($faker->word())
                ->setStreet('15 rue sainte croix des pelletiers')
                ->setZipCode('76000')
                ->setCity('Rouen')
                ->setIsOpened(true)
                ->setLatitude(49.4437)
                ->setLongitude(1.0993)
                ->setFranchise($this->getReference('user.franchiseFranchise'))
                ->setCreatedBy($this->getReference('user.franchise'))
                ->setUpdatedBy($this->getReference('user.franchise'))
                ->addUsers($this->getReference('user.employee'));

                $this->addReference('user.franchiseFranchiseShop', $object);



            $manager->persist($object);


        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [
            FranchiseFixtures::class,
        ];
    }
}
