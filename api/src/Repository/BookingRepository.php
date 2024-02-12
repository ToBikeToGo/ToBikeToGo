<?php

namespace App\Repository;

use App\Entity\Booking;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Booking>
 *
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

//    /**
//     * @return Booking[] Returns an array of Booking objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Booking
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function getUserBookingStartIn2Days()
    {
        $twoDaysLater = new DateTime();
        $twoDaysLater->modify('+2 days');

        return $this->createQueryBuilder('b')
            ->where('b.startDate >= :twoDaysLaterStart')
            ->andWhere('b.startDate < :twoDaysLaterEnd')
            ->setParameter('twoDaysLaterStart', $twoDaysLater->format('Y-m-d 00:00:00'))
            ->setParameter('twoDaysLaterEnd', $twoDaysLater->format('Y-m-d 23:59:59'))
            ->getQuery()
            ->getResult();
    }
}
