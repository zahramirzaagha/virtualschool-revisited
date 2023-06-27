<?php

namespace App\Repository;

use App\Entity\CourseRegistration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CourseRegistration>
 *
 * @method CourseRegistration|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourseRegistration|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourseRegistration[]    findAll()
 * @method CourseRegistration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRegistrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseRegistration::class);
    }

    public function save(CourseRegistration $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CourseRegistration $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByStudentId(int $studentId): array
    {
        return $this->createQueryBuilder('cr')
            ->join("cr.student", "s", "WITH", "s.id = :studentId")
            ->setParameter('studentId', $studentId)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByCourseId(int $courseId): array
    {
        return $this->createQueryBuilder('cr')
            ->join("cr.course", "c", "WITH", "c.id = :courseId")
            ->setParameter('courseId', $courseId)
            ->getQuery()
            ->getResult()
            ;
    }

//    public function findOneBySomeField($value): ?CourseRegistration
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
