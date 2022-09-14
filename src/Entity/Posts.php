<?php

namespace App\Entity;

use App\Repository\PostsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostsRepository::class)
 */
class Posts
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $userId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $title;

    /**
     * @ORM\Column(type="string", length=500)
     */
    public $body;

    public function __construct(array $data = array())
    {
        foreach ($data as $property => $value) {
            $this->$property = $value;
        }
    }

}
