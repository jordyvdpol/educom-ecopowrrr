<?php

namespace App\Repository;

use App\Entity\Prijs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Prijs>
 *
 * @method Prijs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prijs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prijs[]    findAll()
 * @method Prijs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrijsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prijs::class);
    }

    public function save(Prijs $entity, bool $flush = false): bool {
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

    public function remove(Prijs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Prijs[] Returns an array of Prijs objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Prijs
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
