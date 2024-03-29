<?php

namespace App\Repository;

use App\Entity\Books;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Books>
 *
 * @method Books|null find($id, $lockMode = null, $lockVersion = null)
 * @method Books|null findOneBy(array $criteria, array $orderBy = null)
 * @method Books[]    findAll()
 * @method Books[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BooksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Books::class);
    }

    public function save(Books $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Books $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * @return Books[] Returns an array of Books objects
    */
   public function findByName($name): array
   {
       return $this->createQueryBuilder("books")
           ->andWhere('books.title = :val')
           ->setParameter('val', $name)
           ->getQuery()
           ->getResult();
   }

       /**
    * @return Books[] Returns an array of Books objects
    */
    public function findByIsbin($isbin): array
    {

        return $this->createQueryBuilder("books")
            ->andWhere('books.isbin = :isbin')
            ->setParameter('isbin', $isbin)
            ->getQuery()
            ->getResult();
    }


}
