<?php

namespace App\Controller;

use App\Entity\Bike;
use App\Entity\Shop;
use App\Entity\Booking;
use App\Entity\Payment;
use App\Entity\Franchise;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShopStatsAction extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param Request $request
     * @return Response
     *
     * Get all the stats for a shop
     * - Number of bikes
     * - Number of bookings
     * - Number of users
     * - Number of requests
     * can be filtered by date
     */
    public function __invoke(Request $request, Shop $shop): Response
    {
        $content = $request->getContent();
        $params = json_decode($content, true);

        $startDate = isset($params['startDate']) ? (new \DateTime($params['startDate']))->format('Y-m-d') : null;
        $endDate = isset($params['endDate']) ? (new \DateTime($params['endDate']))->format('Y-m-d') : null;

        $shopId = $shop->getId();
        $shopName = $shop->getLabel();

        $bikes = $this->entityManager->getRepository(Bike::class)->createQueryBuilder('b')
            ->select('b.id')
            ->innerJoin('b.shop', 's')
            ->andWhere('s.id = :shop')
            ->setParameter('shop', $shop)
            ->getQuery()
            ->getResult();

        $nbBikes = count($bikes);

        $bookingsAtStartAndEndDate = $this->entityManager->getRepository(Booking::class)->createQueryBuilder('b')
            ->select('COUNT(CASE WHEN b.startDate = :startDate THEN 1 ELSE 0 END) as nbBookingsAtStartDate')
            ->addSelect('COUNT(CASE WHEN b.startDate = :endDate THEN 1 ELSE 0 END) as nbBookingsAtEndDate')
            ->innerJoin('b.bike', 'bike')
            ->innerJoin('bike.shop', 's')
            ->andWhere('s.id = :shop')
            ->setParameter('shop', $shop)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getSingleResult();

        $nbBookingsAtStartDate = $bookingsAtStartAndEndDate['nbBookingsAtStartDate'];
        $nbBookingsAtEndDate = $bookingsAtStartAndEndDate['nbBookingsAtEndDate'];

        $users = $this->entityManager->getRepository(Franchise::class)->createQueryBuilder('f')
            ->select('f.id')
            ->innerJoin('f.shops', 's')
            ->andWhere('s.id = :shop')
            ->setParameter('shop', $shop)
            ->getQuery()
            ->getResult();

        $nbUsers = count($users);

        $payments = $this->entityManager->getRepository(Payment::class)->createQueryBuilder('p')
            ->select('p.price')
            ->innerJoin('p.shop', 's')
            ->andWhere('s.id = :shop')
            ->setParameter('shop', $shop);

        if ($startDate && $endDate) {
            $payments->andWhere('p.createdAt BETWEEN :startDate AND :endDate')
                ->setParameter('startDate', $startDate)
                ->setParameter('endDate', $endDate);
        }

        $payments = $payments->getQuery()->getResult();
        $nbEarned = 0;

        foreach ($payments as $payment) {
            $nbEarned += $payment['price'];
        }

        $query = $this->entityManager->createQueryBuilder()
            ->select(
                'COUNT(b.id) as nbBookings',
                'SUM(CASE WHEN b.rating > 3 THEN 1 ELSE 0 END) as nbSatisfiedBookings'
            )
            ->from(Booking::class, 'b')
            ->innerJoin('b.bike', 'bi')
            ->innerJoin('bi.shop', 's')
            ->where('s.id = :shop')
            ->setParameter('shop', $shopId);

        if ($startDate && $endDate) {
            $query->andWhere('b.startDate BETWEEN :startDate AND :endDate')
                ->setParameter('startDate', $startDate)
                ->setParameter('endDate', $endDate);
        }

        $result = $query->getQuery()->getSingleResult();
        $nbBookings = $result['nbBookings'];
        $nbSatisfiedBookings = $result['nbSatisfiedBookings'];

        if ($startDate) {
            $nbBookingsAtStartDate = $this->entityManager->getRepository(Booking::class)->createQueryBuilder('b')
                ->select('COUNT(CASE WHEN b.startDate = :startDate THEN 1 ELSE 0 END)')
                ->innerJoin('b.bike', 'bike')
                ->innerJoin('bike.shop', 's')
                ->andWhere('s.id = :shop')
                ->setParameter('shop', $shop)
                ->setParameter('startDate', $startDate)
                ->getQuery()
                ->getSingleScalarResult();
        } else {
            $nbBookingsAtStartDate = 0;
        }

        if ($endDate) {
            $nbBookingsAtEndDate = $this->entityManager->getRepository(Booking::class)->createQueryBuilder('b')
                ->select('COUNT(CASE WHEN b.startDate = :endDate THEN 1 ELSE 0 END)')
                ->innerJoin('b.bike', 'bike')
                ->innerJoin('bike.shop', 's')
                ->andWhere('s.id = :shop')
                ->setParameter('shop', $shop)
                ->setParameter('endDate', $endDate)
                ->getQuery()
                ->getSingleScalarResult();
        } else {
            $nbBookingsAtEndDate = 0;
        }

        if ($startDate && $endDate) {
            $query = $this->entityManager->getRepository(Booking::class)->createQueryBuilder('b')
                ->select('COUNT(b) as nbBookings')
                ->addSelect('AVG(b.rating) as avgRating')
                ->innerJoin('b.bike', 'bike')
                ->innerJoin('bike.shop', 's')
                ->where('s.id = :shop')
                ->andWhere('b.startDate <= :endDate AND b.endDate >= :startDate') // Checks overlap
                ->setParameter('shop', $shop)
                ->setParameter('startDate', $startDate)
                ->setParameter('endDate', $endDate);

            $result = $query->getQuery()->getSingleResult();
            $nbBookingsInRange = $result['nbBookings'];
            $avgRatingInRange = $result['avgRating'];
        } else {
            $nbBookingsInRange = 0;
            $avgRatingInRange = null;
        }


        $conn = $this->entityManager->getConnection();
        $sql = 'SELECT
EXTRACT(YEAR FROM b.start_date) AS year,
EXTRACT(MONTH FROM b.start_date) AS month,
COUNT(*) AS nbBookings
FROM
booking b
INNER JOIN bike ON b.bike_id = bike.id
INNER JOIN shop ON bike.shop_id = shop.id
WHERE
shop.id = :shopId
AND b.start_date BETWEEN :startDate AND :endDate
GROUP BY
EXTRACT(YEAR FROM b.start_date), EXTRACT(MONTH FROM b.start_date)
ORDER BY
year DESC, month ASC;
';

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(['shopId' => $shop->getId(), 'startDate' => $startDate, 'endDate' => $endDate]);
        $monthlyBookingsByYear = $result->fetchAllAssociative();

        $formattedMonthlyBookings = [];
        foreach ($monthlyBookingsByYear as $row) {
            $formattedMonthlyBookings[] = [
                'year' => (int) $row['year'],
                'month' => (int) $row['month'],
                'nbBookings' => $row['nbbookings'] ?? 0,
            ];
        }

        $sql = '
               SELECT
            c.type AS category,
            COUNT(*) AS nbBookings
            FROM
            booking b
            INNER JOIN bike ON b.bike_id = bike.id
            INNER JOIN shop ON bike.shop_id = shop.id
            INNER JOIN bike_category c ON bike.category_id = c.id
            WHERE
            shop.id = :shopId
            AND b.start_date BETWEEN :startDate AND :endDate
            GROUP BY
            c.type
            ORDER BY
            nbBookings DESC
            LIMIT 1;
            ';

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(['shopId' => $shop->getId(), 'startDate' => $startDate, 'endDate' => $endDate]);
        $mostBookedCategory = $result->fetchAssociative();

        $mostBookedCategoryName = $mostBookedCategory['category'];
        $mostBookedCategoryBookings = $mostBookedCategory['nbbookings'];

        return $this->json([
            'nbBikes' => $nbBikes,
            'reservationCount' => $nbBookings,
            'employeeCount' => $nbUsers,
            'totalEarned' => $nbEarned,
            'shopId' => $shopId,
            'nbSatisfiedBookings' => $nbSatisfiedBookings,
            'nbBookingsAtStartDate' => $nbBookingsAtStartDate,
            'nbBookingsAtEndDate' => $nbBookingsAtEndDate,
            'nbBookingsInRange' => $nbBookingsInRange,
            'monthlyBookings' => $formattedMonthlyBookings,
            'mostBookedCategory' => $mostBookedCategoryName,
            'mostBookedCategoryBookings' => $mostBookedCategoryBookings,
            'avgRatingInRange' => $avgRatingInRange,
            'shopName' => $shopName,
        ]);
    }
}
