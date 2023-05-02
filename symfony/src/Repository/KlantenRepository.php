<?php

namespace App\Repository;

use App\Entity\Klanten;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Klanten>
 *
 * @method Klanten|null find($id, $lockMode = null, $lockVersion = null)
 * @method Klanten|null findOneBy(array $criteria, array $orderBy = null)
 * @method Klanten[]    findAll()
 * @method Klanten[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KlantenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Klanten::class);
    }

    public function save(Klanten $entity, bool $flush = false): bool {
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



    public function remove(Klanten $enflustity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllById(){
        return $this -> createQueryBuilder('d')
            ->select('d.id')
            ->getQuery()
            ->getResult();
    }



//    /**
//     * @return Klanten[] Returns an array of Klanten objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('k')
//            ->andWhere('k.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('k.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Klanten
//    {
//        return $this->createQueryBuilder('k')
//            ->andWhere('k.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
