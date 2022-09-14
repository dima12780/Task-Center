<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Comments;
use App\Entity\different;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\CommentsRepository;

class CommentsController extends AbstractController
{
    
    protected $repository;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        $this->repository = new CommentsRepository($registry, $em);
    }
    
    /**
     * @Route("/comments/{params}", methods={"GET"}, name="comments_searchOne")
     * @return JsonResponse
     */
    public function search($params)
    {
        return new JsonResponse(
            $this->repository->search($params),
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/comments", methods={"GET"}, name="comments_search")
     * @return JsonResponse
     */
    public function find(Request $request)
    {
        return new JsonResponse(
            $this->repository->findAll(),
            Response::HTTP_OK
        );
    }
    
    /**
     * @Route("/installation/comments", methods={"GET"}, name="comments_install")
     */
    public function installation()
    {
        $request = 'https://jsonplaceholder.typicode.com/comments';
        $request_json = file_get_contents("$request");
        $data = json_decode($request_json, true);
        $coments = $this->repository->installation($data);
        return new JsonResponse("Comments loaded $coments", Response::HTTP_CREATED);
    }

}