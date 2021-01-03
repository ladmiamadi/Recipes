<?php

namespace App\Repository;

use App\Entity\Rating;
use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rating|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rating|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rating[]    findAll()
 * @method Rating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    // /**
    //  * @return Rating[] Returns an array of Rating objects
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

    // cette fonction retourne la liste des rating avec une note moyenne pour chaque recipe
    public function findAvgRating()
    {
        return $this->createQueryBuilder('c')

            ->select('AVG(c.note) AS note')

            ->innerJoin(
                Recipe::class,    // Entity
                'p',               // Alias
                Join::WITH,        // Join type
                'p.id = c.recipe' // Join columns
            )
            ->addSelect('p.id AS recipe')

            ->groupBy('c.recipe')



            ->getQuery()
            ->getResult();
    }
    // cette fonction retourne une note moyenne de la recipe ayant comme id $value
    public function findOneByRecipe($value)
    {
        return $this->createQueryBuilder('c')
            ->select('AVG(c.note) AS note')

            ->Where('c.recipe = :val')


            ->setParameter(':val', $value)



            ->getQuery()
            ->getOneOrNullResult();
    }
}
