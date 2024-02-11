<?php namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Booking;



class GetBookingsByShopAction extends AbstractController
{
    
    private $entityManager;
    
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
public function findByShopId($shopId)
{
    return $this->entityManager->getRepository(Booking::class)->createQueryBuilder('b')
        ->innerJoin('b.bike', 'bike')
        ->where('bike.shop = :shopId')
        ->setParameter('shopId', $shopId)
        ->getQuery()
        ->getResult();
}
    
    public function __invoke($id, Booking $booking): Response
    {
        $bookings = $this->findByShopId($id);
        
        return $this->json($bookings);
        
    }
}



