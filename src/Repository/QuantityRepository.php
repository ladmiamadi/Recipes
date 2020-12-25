<?php

namespace App\Repository;

use App\Entity\Quantity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Quantity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quantity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quantity[]    findAll()
 * @method Quantity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuantityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quantity::class);
    }

    // /**
    //  * @return Quantity[] Returns an array of Quantity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Quantity
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findOneByIdJoinedToIngredient($recipeId)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p.name, c.amount, c.symbol
            FROM App\Entity\Ingredient p
            INNER JOIN p.quantities c
            WHERE c.recipe = :id'
            
        )->setParameter('id', $recipeId);
        

        return $query->getResult();
    }
}
  //  SELECT ingredient.name, quantity.amount, quantity.symbol
//FROM quantity
//INNER JOIN ingredient ON quantity.ingredient_id=ingredient.id
//WHERE quantity.recipe_id=1