<?php

namespace App\Repository;

use App\Entity\PersonalLabels;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PersonalLabels|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonalLabels|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonalLabels[]    findAll()
 * @method PersonalLabels[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonalLabelsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PersonalLabels::class);
    }

    public function findEmailsBySlug(string $slug, int $id): array
    {
        // a = PersonalLabelTable
        // b = LabelTable
        // c = ReceivedSentEmailsToLabelsTable
        // d = ReceivedEmailsTable
        // e = EmailTable
        return $this->createQueryBuilder('a')
                    ->leftJoin('a.label', 'b')
                    ->andWhere('b.slug = :slug')
                    ->andWhere('a.member = :id')
                    ->setParameters([':slug' => $slug, ':id' => $id])
                    ->leftJoin('a.sentOrReceivedEmails', 'c')
                    ->leftJoin('App\Entity\ReceivedEmails', 'd', 'WITH', 'c.receivedEmail = d.id')
                    ->leftJoin('App\Entity\Email', 'e', 'WITH', 'd.email = e.id')
                    ->orderBy('e.timeSent', 'ASC')
                    ->getQuery()
                    ->getResult();
    }
   /**
    * @return PersonalLabels[] Returns an array of PersonalLabels objects
    */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PersonalLabels
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
