<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findLastFour()
    {
        
        return $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findFourMostLiked()
    {

        //here we want to count likes in the article and be able to get the 4 most liked
        return $this->createQueryBuilder('p')
        ->select('count(l) AS HIDDEN nbrLikes', 'p')
        ->leftJoin('p.likes', 'l')
        ->orderBy('nbrLikes', 'DESC')
        ->groupBy('p')
        ->setMaxResults(4)
        ->getQuery()
        ->getResult();
    }
}
