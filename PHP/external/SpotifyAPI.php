<?php
include_once "../entity/User.php";
include_once "../entity/Artist.php";
include_once "../entity/Album.php";
include_once "../entity/Track.php";
include_once "../entity/Playlist.php";


/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/5
 * Time: 18:27
 */
class SpotifyAPI
{
    private static $CLIENT_ID = "7b6c8aaf221f4aba9401096b2e7f1494";
    private static $CLINT_SECRET = "d7e7a8f15c8147d0a1875554627256ce";
    private static $_TOKEN;
    private static $_EXPIRE_TIME;

    public function __construct()
    {
        self::$_TOKEN = self::getToken();
    }


    public static function getToken()
    {
        if (self::$_EXPIRE_TIME != null || strtotime(self::$_EXPIRE_TIME) > date("Y-m-d H-i", time())) {
            return self::$_TOKEN;
        } else {
            self:: $_EXPIRE_TIME = date("Y-m-d H-i", time() + 3600);
            $curl = curl_init();
            curl_setopt_array($curl,
                array(CURLOPT_URL => "https://accounts.spotify.com/api/token",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Basic ' . base64_encode(self::$CLIENT_ID . ':' . self::$CLINT_SECRET)),
                    )
            );
            $result = json_decode(curl_exec($curl), true);
            self::$_TOKEN = $result["access_token"];
            curl_close($curl);
        }
        return self::$_TOKEN;
    }

    public function searchArtist($keyword, $type ="word", $size =10)
    {
        if ($type == "word") {
            $url = "https://api.spotify.com/v1/search?q=" . $keyword . "&type=artist" . "&limit=" . $size;
        } else {
            $url = "https://api.spotify.com/v1/artists/" . $keyword;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Authorization: Bearer " . self::getToken(),
            ),
        ));
        if ($type == "word") {
            $response = json_decode(curl_exec($curl), true)["artists"]["items"];
        } else {
            $response = array();
            $response[0] = json_decode(curl_exec($curl), true);
        }

//        $err = curl_error($curl);
        curl_close($curl);
        return $this->getArtistArray($response);
    }

    public function getRelatedArtist($id)
    {
        $url = "https://api.spotify.com/v1/artists/".$id."/related-artists";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Authorization: Bearer " . self::getToken(),
            ),
        ));

        $response = json_decode(curl_exec($curl), true)["artists"];
//        $err = curl_error($curl);
        curl_close($curl);
        return $this->getArtistArray($response);
    }

    public function getArtistArray(array $artists)
    {
        $artistArray = array();
        for ($i = 0; $i < count($artists); $i++) {
            $genre = array();
            $builder = new ArtistBuilder();
            $imgurl = $this->resize("artist/",$artists[$i]["id"], $artists[$i]["images"][0]["url"]);
            $builder->setId($artists[$i]["id"])->setName($artists[$i]["name"])->setImgurl($imgurl);
            for ($j = 0; $j < count($artists[$i]["genres"]); $j++) {
                array_push($genre, $artists[$i]["genres"][$j]);
            }
            $builder->setGenere($genre);
            array_push($artistArray, $builder->build());
        }
        return $artistArray;
    }

    public function searchAlbum($keyword, $type="word", $size=20)
    {
        if ($type == "id") {
            $url = "https://api.spotify.com/v1/artists/".$keyword."/albums?&limit=".$size;
        } else {
            $url = "https://api.spotify.com/v1/search?q=".$keyword."&type=album"."&limit=".$size;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Authorization: Bearer ".self::getToken(),
            ),
        ));
        if ($type == "word") {
            $response = json_decode(curl_exec($curl), true)["albums"]["items"];
        } else {
            $response = json_decode(curl_exec($curl), true)["items"];
        }
//        $err = curl_error($curl);
        curl_close($curl);
        return $this->getAlbumArray($response);
    }

    public function getNewRelease($size=20)
    {
        $url = "https://api.spotify.com/v1/browse/new-releases?country=US&limit=".$size;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Authorization: Bearer ".self::getToken(),
            ),
        ));

        $response = json_decode(curl_exec($curl), true)["albums"]["items"];
//        $err = curl_error($curl);
        curl_close($curl);
        return $this->getAlbumArray($response);
    }

    public function getAlbumTrack($albumid)
    {
        $url = "https://api.spotify.com/v1/albums/".$albumid;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Authorization: Bearer ".self::getToken(),
            ),
        ));

        $response = json_decode(curl_exec($curl), true);
//        $err = curl_error($curl);
        curl_close($curl);
        return $response;
    }

    public function getAlbumArray($albums) {
        $albumsArray = array();
        for ($i = 0; $i < count($albums); $i++) {
            $tracks = array();
            $album = $this->getAlbumTrack($albums[$i]["id"]);
            $builder = new AlbumBuilder();
            $imgurl = $this->resize("album/", $albums[$i]["id"], $album["images"][0]["url"]);
            $builder->setalid($album["id"])->setName($album["name"])->setImgurl($imgurl)
                ->setDate($album["release_date"])->setAid($album["artists"][0]["id"])->setAname($album["artists"][0]["name"]);
            for ($j = 0; $j < count($album["tracks"]["items"]); $j++) {
                array_push($tracks, $this->getTrack($album["tracks"]["items"][$j], $album["name"]));
            }
            $builder->setTracks($tracks);
            array_push($albumsArray, $builder->build());
        }
        return $albumsArray;
    }

    public function getTrack(array $track, $alname) {
        $builder = new TrackBuilder();
        $ms = $track["duration_ms"];
        $duration = intval($ms/60000).":".$ms/1000%60;
        $builder->setTid($track["id"])->setName($track["name"])->setAname($track["artists"][0]["name"])
            ->setScore("0")->setUrl($track["external_urls"]["spotify"])->setDuration($duration)->setAlname($alname);
        return $builder->build();
    }

    public function resize($type, $id, $url) {
        $img = file_get_contents($url);

        $im = imagecreatefromstring($img);

        $width = imagesx($im);

        $height = imagesy($im);

        $newwidth = '320';

        $newheight = '320';

        $thumb = imagecreatetruecolor($newwidth, $newheight);

        imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        $target = '../../img/cover/'.$type.$id.'.jpg';
        imagejpeg($thumb, $target); //save image as jpg

        imagedestroy($thumb);

        imagedestroy($im);
        return $target;
    }
}