<?php

namespace App\Repository;

use App\Entity\Contacts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contacts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contacts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contacts[]    findAll()
 * @method Contacts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contacts::class);
    }

    public function update($values){
        $qb = $this->createQueryBuilder();
        $qb->update('u')
        ->set('u.name', $qb->expr()->literal($username))
        ->set('u.email', $qb->expr()->literal($email))
        ->set('u.phone', $qb->expr()->literal($email))
        ->where('u.id = ?1')
        ->setParameter(1, $editId)
        ->getQuery();
    }

    // /**
    //  * @return Contacts[] Returns an array of Contacts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Contacts
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    // /**
    //  * @return Contacts[] Returns an array of Contacts objects
    //  */
    

    public function findByNameLike($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.name LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult();
    }
}
