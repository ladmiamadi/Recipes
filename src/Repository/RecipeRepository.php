<?php

namespace App\Repository;

use App\Entity\Recipe;
use App\Entity\RecipeSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    // /**
    //  * @return Recipe[] Returns an array of Recipe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function findByCategory($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.category = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')

            ->getQuery()
            ->getResult();
    }
    public function findRandomRecipes()
    {

        return $this->createQueryBuilder('r')
            ->orderBy('RAND()')



            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Recipe
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * findAllRecipes
     *
     * @param  RecipeSearch $search
     *
     */
    public function findAllRecipes(RecipeSearch $search)
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('RAND()');

        if ($search->getTitleSearch()) {
            $query = $query
                ->andWhere('p.title LIKE :title')
                ->setParameter('title', '%' . $search->getTitleSearch() . '%');
        }
        return $query->getQuery()->execute();
    }
}
