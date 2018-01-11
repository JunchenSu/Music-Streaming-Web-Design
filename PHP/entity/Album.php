<?php
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/6
 * Time: 21:47
 */

class Album
{
    public $alid;
    public $name;
    public $aid;
    public $imgurl;
    public $date;
    public $tracks;

    public $aname;
    public $like;

    /**
     * Album constructor.
     * @param $alid
     * @param $name
     * @param $aid
     * @param $imgurl
     * @param $date
     */
    public function __construct(AlbumBuilder $builder)
    {
        $this->alid = $builder->alid;
        $this->name = $builder->name;
        $this->aid = $builder->aid;
        $this->imgurl = $builder->imgurl;
        $this->date = $builder->date;
        $this->tracks = $builder->tracks;
        $this->aname = $builder->aname;
        $this->like = $builder->like;
    }

    /**
     * @return mixed
     */
    public function getAlid()
    {
        return $this->alid;
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
    public function getAid()
    {
        return $this->aid;
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
    public function getDate()
    {
        return $this->date;
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
    public function getAname()
    {
        return $this->aname;
    }

    /**
     * @return mixed
     */
    public function getLike()
    {
        return $this->like;
    }



}
class AlbumBuilder{
    public $alid;
    public $name;
    public $aid;
    public $imgurl;
    public $date;
    public $tracks;

    public $aname;
    public $like;

    /**
     * @param mixed $alid
     */
    public function setAlid($alid)
    {
        $this->alid = $alid;
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
     * @param mixed $aid
     */
    public function setAid($aid)
    {
        $this->aid = $aid;
        return $this;
    }

    /**
     * @param mixed $imgurl
     */
    public function setImgurl($imgurl)
    {
        $this->imgurl = $imgurl;
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
     * @param mixed $aname
     */
    public function setAname($aname)
    {
        $this->aname = $aname;
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
        return new Album($this);
    }
}