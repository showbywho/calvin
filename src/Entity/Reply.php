<?php

namespace App\Entity;

use App\Repository\ReplyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReplyRepository::class)
 * @ORM\Table(name = "Reply")
 */
class Reply
{
    /**
     * @ORM\Id
     * @ORM\Column(name = "id", type = "integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name = "contents", type = "string", length = 100, nullable = false, options  =  {"comment"  =  "留言內容"})
     */
    private $contents;

    /**
     * @ORM\Column(name = "owner", type = "string", length = 100, nullable = false, options  =  {"comment"  =  "留言者暱稱"})
     */
    private $owner;

    /**
     * @ORM\Column(name = "times", type = "string", length = 100, nullable = false, options  =  {"comment"  =  "留言時間"})
     */
    private $times;

    /**
     * @ORM\Column(name = "user_ip", type = "string", length = 100, nullable = false, options  =  {"comment"  =  "留言者IP"})
     */
    private $userIp;

    /**
     * @ORM\Column(name = "tag", type = "integer", nullable = true)
     */
    private $tag;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getContents()
    {
        return $this->contents;
    }

    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }

    public function getOwner()
    {
        return $this->owner;
    }
    
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    public function getTimes()
    {
        return $this->times;
    }

    public function setTimes($times)
    {
        $this->times = $times;

        return $this;
    }

    public function getUserIp()
    {
        return $this->userIp;
    }

    public function setUserIp($userIp)
    {
        $this->userIp = $userIp;

        return $this;
    }

    public function getTag()
    {
        return $this->tag;
    }

    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }
}
