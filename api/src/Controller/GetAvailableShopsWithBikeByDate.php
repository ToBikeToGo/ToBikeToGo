<?php

namespace App\Controller;

use App\Entity\Bike;
use App\Entity\Request;
use App\Entity\Shop;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetAvailableShopsWithBikeByDate extends AbstractController
{


    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    // get all shops with available bikes by date
    public function __invoke(\Symfony\Component\HttpFoundation\Request $request): Response
    {

        $content = $request->getContent();
        $params = json_decode($content, true);
        $startDate = new \DateTime($params['startDate']);
        $endDate = new \DateTime($params['endDate']);

       $shops = $this->entityManager->getRepository(Bike::class)->createQueryBuilder('b')
            ->select('s.id, s.label, s.address, s.isOpened, b.id as bikeId, b.label as bikeLabel, b.price')
            ->innerJoin('b.shop', 's')
            ->leftJoin('b.bookings', 'bookings')
            ->where('bookings.startDate NOT BETWEEN :startDate AND :endDate')
            ->andWhere('bookings.endDate NOT BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();

        return $this->json([
            'startDate' => $startDate,
            'endDate' => $endDate,
            'shops' => $shops
        ]);

    }
}
