<?php

namespace App\Controller;

use App\Entity\Bike;
use App\Entity\Request;
use App\Entity\Shop;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetAvailableBikesInShopByDate extends AbstractController
{


    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    // get all shops with available bikes by date
    public function __invoke(\Symfony\Component\HttpFoundation\Request $request, Shop $shop): Response
    {

        $content = $request->getContent();
        $params = json_decode($content, true);
        $startDate = new \DateTime($params['startDate']);
        $endDate = new \DateTime($params['endDate']);



       $bikes = $this->entityManager->getRepository(Bike::class)->createQueryBuilder('b')
            ->select('b.id, b.label, b.price, b.brand')
            ->innerJoin('b.shop', 's')
            ->leftJoin('b.bookings', 'bookings')
            ->where('bookings.startDate NOT BETWEEN :startDate AND :endDate')
            ->andWhere('bookings.endDate NOT BETWEEN :startDate AND :endDate')
            ->andWhere('s.id = :shop')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('shop', $shop)
            ->getQuery()
            ->getResult();

        return $this->json($bikes);

    }
}
