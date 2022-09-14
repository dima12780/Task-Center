<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Posts;

/**
 * @extends ServiceEntityRepository<Posts>
 *
 * @method Posts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Posts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Posts[]    findAll()
 * @method Posts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostsRepository extends ServiceEntityRepository
{

    protected $entityManager;
    protected $class;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        $this->class = Posts::class;
        parent::__construct($registry, $this->class);
        $this->entityManager = $entityManager;
    }

    public function search($options = [])
    {
        $entity = $this->entityManager
            ->getRepository($this->class)
            ->findBy(array_merge(
                $options
        ));
        foreach($entity as $vaule)
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
            $post = new Posts($array);
            $this->save($post);
            $i++;
        };
        return $i;
    }
}
