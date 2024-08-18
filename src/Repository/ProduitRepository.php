<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

//    /**
//     * @return Produit[] Returns an array of Produit objects
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

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findByFilters($categorie, $cremeNumberCake, $ganacheLayerCake, $nbPart)
        {
            $qb = $this->createQueryBuilder('p');

            if ($categorie) {
                $qb->andWhere('p.categorie = :categorie')
                ->setParameter('categorie', $categorie);
            }

            if ($cremeNumberCake) {
                $qb->andWhere('p.cremeNumberCake = :cremeNumberCake')
                ->setParameter('cremeNumberCake', $cremeNumberCake);
            }

            if ($ganacheLayerCake) {
                $qb->andWhere('p.ganacheLayerCake = :ganacheLayerCake')
                ->setParameter('ganacheLayerCake', $ganacheLayerCake);
            }

            if ($nbPart) {
                $qb->andWhere('p.nbPart = :nbPart')
                ->setParameter('nbPart', $nbPart);
            }

            return $qb->getQuery()->getResult();
        }
}
