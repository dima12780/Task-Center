<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Comments;

/**
 * @extends ServiceEntityRepository<Comments>
 *
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
{
    protected $entityManager;
    protected $class;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        $this->class = Comments::class;
        parent::__construct($registry, $this->class);
        $this->entityManager = $entityManager;
    }

    public function search($options = [])
    {
        $qb = $this->entityManager->createQueryBuilder();
        $expr = $qb->expr();
        $query = $qb->select('u') 
            ->from($this->class, 'u')
            ->where($expr->like($expr->lower('u.body'), $expr->lower(':options')))
            ->setParameter('options', "%$options%")
            ->getQuery()
            ->execute();
        $response = [];
        foreach($query as $vaule)
        {
            $response[] =  get_object_vars($vaule);
        }
        return $response;
    }

    public function searchOne($id)
    {
        $entity = $this->entityManager
        ->getRepository($this->class)
        ->findBy(array_merge([ "id" => $id]));
        return $entity;
    }

    public function save($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush($entity);
    }

    public function installation($data)
    {
        $i = 0;
        foreach ($data as $key => $array) {
            if ($this->searchOne($array["id"])) continue;
            $comments = new Comments($array);
            $this->save($comments);
            $i++;
        };
        return $i;
    }
}
