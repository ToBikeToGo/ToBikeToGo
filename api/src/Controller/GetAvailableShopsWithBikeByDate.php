<?php

namespace App\Controller;

use App\Entity\Bike;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetAvailableShopsWithBikeByDate extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(Request $request): Response
    {
        $content = $request->getContent();
        $params = json_decode($content, true);
        $startDate = new \DateTime($params['startDate']);
        $endDate = new \DateTime($params['endDate']);

        $shops = $this->entityManager->getRepository(Bike::class)->createQueryBuilder('b')
            ->select("s.id, s.label, concat(s.street, ', ', s.zipCode, ' ', s.city) as address, s.isOpened, b.id as bikeId, b.label as bikeLabel, b.price")
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
