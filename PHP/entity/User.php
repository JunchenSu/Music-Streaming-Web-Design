<?php
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/8
 * Time: 00:41
 */

class User
{
    public $username;
    public $city;
    public $email;
    public $imguri;
    public $playlist;
    public $name;

    public $like;

    /**
     * User constructor.
     * @param $username
     * @param $city
     * @param $email
     * @param $imguri
     * @param $playlist
     */
    public function __construct(UserBuilder $builder)
    {
        $this->username = $builder->username;
        $this->city = $builder->city;
        $this->email = $builder->email;
        $this->imguri = $builder->imguri;
        $this->playlist = $builder->playlist;
        $this->name = $builder->name;
        $this->like = $builder->like;
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
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getImguri()
    {
        return $this->imguri;
    }

    /**
     * @return mixed
     */
    public function getPlaylist()
    {
        return $this->playlist;
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
    public function getLike()
    {
        return $this->like;
    }



}
class UserBuilder{
    public $username;
    public $city;
    public $email;
    public $imguri;
    public $playlist;
    public $name;

    public $like;
    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param mixed $imguri
     */
    public function setImguri($imguri)
    {
        $this->imguri = $imguri;
        return $this;
    }

    /**
     * @param mixed $playlist
     */
    public function setPlaylist($playlist)
    {
        $this->playlist = $playlist;
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
     * @param mixed $like
     */
    public function setLike($like)
    {
        $this->like = $like;
        return $this;
    }



    public function build() {
        return new User($this);
    }
}