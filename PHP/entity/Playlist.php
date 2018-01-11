<?php
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/6
 * Time: 21:58
 */

class Playlist
{
    public $id;
    public $access;
    public $username;
    public $name;
    public $date;
    public $imgurl;
    public $tracks;

    public $like;


    /**
     * Playlist constructor.
     * @param $id
     * @param $access
     * @param $username
     * @param $name
     * @param $date
     * @param $uri
     */
    public function __construct(PlaylistBuilder $builder)
    {
        $this->id = $builder->id;
        $this->access = $builder->access;
        $this->username = $builder->username;
        $this->name = $builder->name;
        $this->date = $builder->date;
        $this->imgurl = $builder->imgurl;
        $this->tracks = $builder->tracks;
        $this->like = $builder->like;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getImgurl()
    {
        return $this->imgurl;
    }


    /**
     * @return mixed
     */
    public function getTracks()
    {
        return $this->tracks;
    }

    /**
     * @return mixed
     */
    public function getLike()
    {
        return $this->like;
    }



}
class PlaylistBuilder{
    public $id;
    public $access;
    public $username;
    public $name;
    public $date;
    public $imgurl;
    public $tracks;
    public $like;

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param mixed $access
     */
    public function setAccess($access)
    {
        $this->access = $access;
        return $this;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @param mixed $tracks
     */
    public function setTracks($tracks)
    {
        $this->tracks = $tracks;
        return $this;
    }

    /**
     * @param mixed $uri
     */
    public function setUri($imgurl)
    {
        $this->imgurl = $imgurl;
        return $this;
    }

    /**
     * @param mixed $like
     */
    public function setLike($like)
    {
        $this->like = $like;
        return $this;
    }



    public function build() {
        return new Playlist($this);
    }


}