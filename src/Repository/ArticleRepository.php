<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Article;
use Doctrine\ORM\EntityManager;

/**
 * 
 */
class ArticleRepository
{   
    private EntityManager $em;

    public function __construct()
    {
        global $container;

        $this->em = $container->get(EntityManager::class);
    }

    public function save(Article $entity, bool $flush = false): void
    {
        $this->em->persist($entity);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->em->remove($entity);

        if ($flush) {
            $this->em->flush();
        }
    }
    
    public function getLastPublishedArticle(): mixed
    {
        $queryBuilder = $this->em->createQueryBuilder();

        $queryBuilder->select('a')
        ->from(Article::class, 'a')
        ->where('a.state = :state')
        ->orderBy('a.created', 'DESC')
        ->setParameter('state', 1)
        ->setMaxResults(1);
     
        $query = $queryBuilder->getQuery();

        return $query->getOneOrNullResult();
    }

    public function getPublishedArticles(): mixed {
        
        $queryBuilder = $this->em->createQueryBuilder();

        $queryBuilder->select('a')
        ->from(Article::class, 'a')
        ->where('a.state = :state')
        ->orderBy('a.created', 'DESC')
        ->setParameter('state', 1);
     
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

//    /**
//     * @return Article[] Returns an array of Article objects
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

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
