<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Posts;
use App\Entity\different;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\PostsRepository;

class PostsController extends AbstractController
{
    
    protected $repository;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        $this->repository = new PostsRepository($registry, $em);
    }
    
    /**
     * @Route("/posts/{params}", methods={"GET"}, name="posts_searchOne")
     */
    public function findOne($params)
    {
        return new JsonResponse(
            $this->repository->findOne($params),
            Response::HTTP_OK
        );
    }
    
    /**
     * @Route("/posts", methods={"GET"}, name="posts_search")
     */
    public function find(Request $request)
    {
        return new JsonResponse(
            $this->repository->findAll(),
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/installation/posts", methods={"GET"}, name="posts_install")
     */
    public function installation()
    {
        require_once '../src/art.php';
        $request = 'https://jsonplaceholder.typicode.com/posts';
        $request_json = file_get_contents("$request");
        $data = json_decode($request_json, true);
        $post = $this->repository->installation($data);
        return new JsonResponse("Posts loaded $post", Response::HTTP_CREATED);
    }

}