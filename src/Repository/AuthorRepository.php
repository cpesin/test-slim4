<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Author;
use Doctrine\ORM\EntityManager;

/**
 * 
 */
class AuthorRepository
{
    private EntityManager $em;

    public function __construct()
    {
        global $container;
        
        $this->em = $container->get(EntityManager::class);
    }

    public function save(Author $entity, bool $flush = false): void
    {
        $this->em->persist($entity);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Author $entity, bool $flush = false): void
    {
        $this->em->remove($entity);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function getAuthors(): mixed 
    {  
        $queryBuilder = $this->em->createQueryBuilder();

        $queryBuilder->select('a')->from(Author::class, 'a');
     
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

//    /**
//     * @return Author[] Returns an array of Author objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Author
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
