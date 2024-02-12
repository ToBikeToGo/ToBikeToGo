<?php

namespace App\Controller;

use App\Entity\Bike;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetAvailableDateForABike extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    // should return the unavailable range for the bike
    // [
    //     {
    //         "startDate": "2021-01-01",
    //         "endDate": "2021-01-02"
    //     },
    //     {
    //         "startDate": "2021-01-05",
    //         "endDate": "2021-01-06"
    //   }
    // ]
    public function __invoke(Request $request, Bike $bike): Response
    {
        $queryBuilder = $this->entityManager->getRepository(Bike::class)->createQueryBuilder('b')
            ->select('bookings.startDate, bookings.endDate')
            ->leftJoin('b.bookings', 'bookings')
            ->andWhere('b.id = :bike')
            ->setParameter('bike', $bike);

        $bookings = $queryBuilder->getQuery()->getResult();

        return $this->json($bookings);
  }
}
