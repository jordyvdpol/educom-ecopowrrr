<?php

namespace App\Repository;

use App\Entity\DummyData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DummyData>
 *
 * @method DummyData|null find($id, $lockMode = null, $lockVersion = null)
 * @method DummyData|null findOneBy(array $criteria, array $orderBy = null)
 * @method DummyData[]    findAll()
 * @method DummyData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DummyDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DummyData::class);
    }
    

    public function save(DummyData $entity, bool $flush = false): bool {
    $this->getEntityManager()->persist($entity);
    try { 
        if ($flush) {
            $this->getEntityManager()->flush();
            return true;
        } 
    } catch (\Exception $e) {
        return false;
    }
    return false;
}

    public function remove(DummyData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findAllKlantnummer($value): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.klantnummer = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    // public function findAllById()
    // {
    //     $qb = $this->createQueryBuilder('d')
    //                ->select('d', 'f') // include foreign entity
    //                ->leftJoin('d.klantnummer', 'f');
    //     return $qb->getQuery()->getArrayResult();
    // }
    


    public function findAllById(){
        return $this -> createQueryBuilder('d')
            ->select('d.id')
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return DummyData[] Returns an array of DummyData objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DummyData
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


}


    
