<?php

namespace App\Controller;

use App\Entity\Bike;
use App\Entity\Shop;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class GetAvailableDateForABike extends AbstractController
{


    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
    public function __invoke(\Symfony\Component\HttpFoundation\Request $request, Bike $bike): Response
    {
        $content = $request->getContent();
        $params = json_decode($content, true);


        $queryBuilder = $this->entityManager->getRepository(Bike::class)->createQueryBuilder('b')
            ->select('bookings.startDate, bookings.endDate')
            ->leftJoin('b.bookings', 'bookings')
            ->andWhere('b.id = :bike')
            ->setParameter('bike', $bike);


        $bookings = $queryBuilder->getQuery()->getResult();

        return $this->json($bookings);
  }
}
