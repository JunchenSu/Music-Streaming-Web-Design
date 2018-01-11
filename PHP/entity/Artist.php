<?php
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/6
 * Time: 14:17
 */

class Artist
{
    public $id;
    public $name;
    public $imgurl;
    public $genere;

    public $like;

    /**
     * Artist constructor.
     */

    public function __construct(ArtistBuilder $builder)
    {
        $this->id = $builder->id;
        $this->name = $builder->name;
        $this->imgurl = $builder->imgurl;
        $this->genere = $builder->genere;
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
    public function getName()
    {
        return $this->name;
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
    public function getGenere()
    {
        return $this->genere;
    }

    /**
     * @return mixed
     */
    public function getLike()
    {
        return $this->like;
    }


}
class ArtistBuilder
{
    public $id;
    public $name;
    public $imgurl;
    public $genere;
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
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @param mixed $genere
     */
    public function setGenere($genere)
    {
        $this->genere = $genere;
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



    public function build()
    {
        return new Artist($this);
    }
}