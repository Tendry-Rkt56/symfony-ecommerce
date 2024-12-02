<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Product::class);
    }

    public function productsLength()
    {
        return $this->getEntityManager()->createQuery(
            "SELECT COUNT(p.id) AS count FROM App\\Entity\\Product p WHERE p.id > 0"
        )->getSingleScalarResult();
    }

    public function getAll(int $page, string $search = '', ?int $categoryId = null)
    {
        return $this->paginator->paginate(
            $this->query($categoryId, $search),
            $page,
            10, 
            []
        );
    }

    public function query(?int $categoryId = null, string $search)
    {
        if (!is_null($categoryId) && $categoryId != -1) {
            return $this->createQueryBuilder('p')->leftJoin('p.category', 'c')->select('p', 'c')->where('p.name LIKE :search')->andWhere('c.id = :id')->setParameter('search', '%'.$search.'%')->setParameter('id', $categoryId);
        }
        return $this->createQueryBuilder('p')->leftJoin('p.category', 'c')->select('p', 'c')->where('p.name LIKE :search')->setParameter('search', '%'.$search.'%');
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
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

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
