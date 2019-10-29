<?php


namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public  function FindForSidebar()
    {
        return $this->createQueryBuilder('c')
                ->join('c.author','a')
                ->select('a.username','c.publishedAt','c.content')
                ->orderBy('c.publishedAt','DESC')
                ->setMaxResults(5)
                ->getQuery()
                ->getResult();
    }


}