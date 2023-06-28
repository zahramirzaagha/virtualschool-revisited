<?php

namespace App\Repository;

use App\Entity\CourseRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CourseRate>
 *
 * @method CourseRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourseRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourseRate[]    findAll()
 * @method CourseRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseRate::class);
    }

    public function save(CourseRate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CourseRate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCourseRater(int $courseId, int $raterId): ?CourseRate
    {
        try {
            return $this->createQueryBuilder('cr')
                ->join("cr.course", "c", "WITH", "c.id = :courseId")
                ->join("cr.rater", "r", "WITH", "r.id = :raterId")
                ->setParameter('courseId', $courseId)
                ->setParameter('raterId', $raterId)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException) {
            return null;
        } catch (NonUniqueResultException $e) {
        }
    }

//    public function findOneBySomeField($value): ?CourseRate
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
