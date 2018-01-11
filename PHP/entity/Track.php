<?php
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/6
 * Time: 21:53
 */

class Track
{
    public $tid;
    public $name;
    public $duration;
    public $score;
    public $url;

    public $aname;
    public $alname;
    public $htime;
    public $alid;
    public $aid;
    /**
     * Track constructor.
     * @param $tid
     * @param $name
     * @param $duration
     * @param $aid
     * @param $score
     * @param $url
     */
    public function __construct(TrackBuilder $builder)
    {
        $this->tid = $builder->tid;
        $this->name = $builder->name;
        $this->duration = $builder->duration;
        $this->score = $builder->score;
        $this->url = $builder->url;
        $this->aname = $builder->aname;
        $this->alname = $builder->alname;
        $this->htime = $builder->htime;
        $this->alid = $builder->alid;
        $this->aid = $builder->aid;
    }

    /**
     * @return mixed
     */
    public function getTid()
    {
        return $this->tid;
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
    public function getDuration()
    {
        return $this->duration;
    }


    /**
     * @return mixed
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
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
    public function getAlname()
    {
        return $this->alname;
    }

    /**
     * @return mixed
     */
    public function getHtime()
    {
        return $this->htime;
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
    public function getAid()
    {
        return $this->aid;
    }


}
class TrackBuilder{
    public $tid;
    public $name;
    public $duration;
    public $score;
    public $url;

    public $aname;
    public $alname;
    public $htime;
    public $alid;
    public $aid;

    /**
     * @param mixed $tid
     */
    public function setTid($tid)
    {
        $this->tid = $tid;
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
     * @param mixed $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @param mixed $score
     */
    public function setScore($score)
    {
        $this->score = $score;
        return $this;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
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
     * @param mixed $alname
     */
    public function setAlname($alname)
    {
        $this->alname = $alname;
        return $this;
    }

    /**
     * @param mixed $htime
     */
    public function setHtime($htime)
    {
        $this->htime = $htime;
        return $this;
    }

    /**
     * @param mixed $alid
     */
    public function setAlid($alid)
    {
        $this->alid = $alid;
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


    public function build() {
        return new Track($this);
    }
}