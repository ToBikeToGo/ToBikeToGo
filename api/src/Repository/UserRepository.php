<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Auth\User;
use Scienta\DoctrineJsonFunctions\Query\AST\Functions\Postgresql\JsonbExistsAll;


class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    //    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findProvidersInFranchisesOfUser($userId): array
    {
        $userFranchises = $this->createQueryBuilder('u')
            ->select('f.id') // Sélectionnez l'ID des franchises de l'utilisateur donné
            ->leftJoin('u.franchises', 'f')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();

        // Récupérez les utilisateurs appartenant aux mêmes franchises que l'utilisateur donné
        return $this->createQueryBuilder('u')
            ->leftJoin('u.franchises', 'f')
            ->andWhere('f.id IN (:franchiseIds)')
            ->setParameter('franchiseIds', $userFranchises)
            ->getQuery()
            ->getResult();
    }


}
