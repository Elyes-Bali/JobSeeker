<?php

namespace App\Repository;

use App\Entity\Offers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offers>
 */
class OffersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offers::class);
    }

//    /**
//     * @return Offers[] Returns an array of Offers objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Offers
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

 // Nombre total d'offres par spécialité
 public function countBySpeciality(): array
 {
     return $this->createQueryBuilder('o')
         ->select('o.specialite AS speciality, COUNT(o.id) AS count')
         ->groupBy('o.specialite')
         ->getQuery()
         ->getResult();
 }

 // Nombre total d'offres par ville
 public function countByCity(): array
 {
     return $this->createQueryBuilder('o')
         ->select('o.villeTravail AS city, COUNT(o.id) AS count')
         ->groupBy('o.villeTravail')
         ->getQuery()
         ->getResult();
 }

 // Nombre d'offres par mois
 public function countByMonth(): array
 {
     return $this->createQueryBuilder('o')
         ->select('MONTH(o.createdAt) AS month, COUNT(o.id) AS count')
         ->groupBy('month')
         ->getQuery()
         ->getResult();
 }

 // Répartition des offres par type de contrat
 public function countByContractType(): array
 {
     return $this->createQueryBuilder('o')
         ->select('o.contrat AS contractType, COUNT(o.id) AS count')
         ->groupBy('o.contrat')
         ->getQuery()
         ->getResult();
 }

 public function findByCurrentUserId(int $currentUserId)
 {
     return $this->createQueryBuilder('o')
         ->where('o.selectedApplayerId = :currentUserId')
         ->setParameter('currentUserId', $currentUserId)
         ->getQuery()
         ->getResult();
 }
 
 
}
