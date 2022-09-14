<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\different;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\CommentsRepository;
use App\Repository\PostsRepository;


class IndexController extends AbstractController
{

    protected $commentsController;
    protected $PostsRepository;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        $this->commentsRepository = new CommentsRepository($registry, $em);
        $this->postsRepository = new PostsRepository($registry, $em);
    }

    /**
     * @Route("/index", methods={"GET"}, name="index")
     */
    public function index()
    {
        if(isset($_GET["serch"]) && strlen($_GET["serch"]) >= 3)
        {
            $query = $this->commentsRepository->search($_GET["serch"]);
        }elseif(strlen($_GET["serch"]) === 0) echo "";
        else echo "введите больше символов для поиска!";
        $response = new Response();
        $txt = "<div>
            <form method='GET' enctype='multipart/form-data'>
                <br><input type='text' name='serch' placeholder=''/><br>
                <br><input type='submit' value='Поиск'/>
            </form>
        <table border='1'><tr>
                <th>Post title</th>
                <th>Coment name</th>
                <th>Coment body</th>
            </tr><tr>";
        foreach ($query as $key => $value) {
            $number = $this->postsRepository->search(["id" => $value["postId"]]);
            $txt =  $txt ."<td>".$number[0]['title']."</td><td>".$value['name']."</td><td>".$value['body']."</td></tr>";
        }
        $txt =  $txt ."</table></div>";

        return $response->setContent($txt);
    }

    /**
     * @Route("/installation", methods={"GET"}, name="installation")
     */
    public function installation()
    {
        $conn = ["posts", "comments"];
        $request = 'https://jsonplaceholder.typicode.com/';
        foreach ($conn as $key => $value) {
            $request_json = file_get_contents($request.$value);
            $data = json_decode($request_json, true);
            $rep = $value."Repository"; 
            $num[] = $this->$rep->installation($data);
        }

        return new Response("Loaded ".$num[0]." records and ".$num[1]." comments");
    }
}