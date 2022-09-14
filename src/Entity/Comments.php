<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 */
class Comments
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $postId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=60)
     */
    public $email;

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
