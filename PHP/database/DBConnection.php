<?php include_once "../external/SpotifyAPI.php";

/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/5
 * Time: 20:23
 */
class DBConnection
{
    private static $_instance;
    private $link;
    private $server = "localhost:3307";
    private $username = "root";
    private $password = "root";
    private $dbname = "final";

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        $this->link = new mysqli($this->server, $this->username, $this->password, $this->dbname);
        if ($this->link->connect_error) {
            die("Could not connect: " . $this->link->connect_error);
        }
    }

    private function __clone()
    {
    }

//    private function getConnection()
//    {
//        return $this->link;
//    }

    private function prepare($query)
    {
        $result = $this->link->prepare($query);
        return $result;
    }

    public function searchPlaylist($keyword, $username)
    {
        $playlists = array();
        $keyword = "%{$keyword}%";
        $sql = "SELECT pid, username, pname, ptime, pimguri FROM Playlist WHERE username <> ? AND access = 'public' AND pname LIKE ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('ss', $username, $keyword);
        $stmt->execute();
        $stmt->bind_result($pid, $username, $pname, $ptime, $pimguri);
        while ($stmt->fetch()) {
            $builder = new PlaylistBuilder();
            $builder->setId($pid)->setUsername($username)->setName($pname)->setDate($ptime)->setUri($pimguri);
            array_push($playlists, $builder->build());
        }
        $stmt->close();
        return $playlists;
    }

    public function searchTrack()
    {
        return 111;
    }

    public function upload($target_file, $path, $id, $name = "")
    {
        if ($path == "account") {
            $sql = "UPDATE User SET imguri = ? WHERE username = ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('ss', $target_file, $id);
        } else {
            $sql = "UPDATE Playlist SET pimguri = ? WHERE username = ? AND pname = ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('sss', $target_file, $id, $name);
        }

        if ($stmt->execute() === TRUE) {
            $stmt->close();
            return "OK";
        } else {
            $stmt->close();
            return "ERROR";
        }
    }

    public function getPlaylistByName($user, $name)
    {
        $sql = "SELECT ptime access FROM Playlist WHERE username = ? AND pname = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('ss', $user, $name);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->close();
            return "EXIST";
        } else {
            $stmt->close();
            return "OK";
        }
    }

    public function signin($username, $password)
    {
        $sql = "SELECT username FROM User WHERE username = ? AND password = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function getLikedArtistid($username)
    {
        $artists = array();
        $sql = "SELECT aid FROM LikeArtist WHERE username = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($aid);
        while ($stmt->fetch()) {
            array_push($artists, $aid);
        }
        $stmt->close();
        return $artists;
    }

    public function searchArtist($keyword, $type = "word", $size = 20)
    {
        $api = new SpotifyAPI();
        $artists = $api->searchArtist($keyword, $type, $size);
        for ($i = 0; $i < count($artists); $i++) {
            $this->saveArtist($artists[$i]);
        }
        return $artists;
    }

    public function getRelatedArtist($id)
    {
        $api = new SpotifyAPI();
        $artists = $api->getRelatedArtist($id);
        for ($i = 0; $i < count($artists); $i++) {
            $this->saveArtist($artists[$i]);
        }
        return $artists;
    }

    public function saveArtist(Artist $artist)
    {
        $sql = "INSERT INTO Artist (aid, aname, imgurl) VALUES (?, ?, ?)";
        $stmt = $this->prepare($sql);
        $id = $artist->getid();
        $name = $artist->getName();
        $imgurl = $artist->getImgurl();
        $stmt->bind_param('sss', $id, $name, $imgurl);
        $stmt->execute();
        $stmt->close();

        for ($i = 0; $i < count($artist->getGenere()); $i++) {
            $sql = "INSERT INTO ArtistGenere (aid, agenre) VALUES (?, ?)";
            $stmt = $this->prepare($sql);
            $genre = $artist->getGenere()[$i];
            $stmt->bind_param('ss', $id, $genre);
            $stmt->execute();
            $stmt->close();
        }
    }

    public function searchAlbum($keyword, $type = "word", $size = 20)
    {
        $api = new SpotifyAPI();
        $albums = $api->searchAlbum($keyword, $type, $size);
        for ($i = 0; $i < count($albums); $i++) {
            $aid = $albums[$i]->getAid();
            $sql = "SELECT aname FROM Artist WHERE aid = ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('s', $aid);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows == 0) {
                $this->searchArtist($aid, "id");
            }
            $stmt->close();
            $this->saveAlbum($albums[$i]);
        }
        return $albums;
    }

    public function getNewRelease($size = 20)
    {
        $api = new SpotifyAPI();
        $albums = $api->getNewRelease($size);
        for ($i = 0; $i < count($albums); $i++) {
            $aid = $albums[$i]->getAid();
            $sql = "SELECT aname FROM Artist WHERE aid = ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('s', $aid);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows == 0) {
                $this->searchArtist($aid, "id");
            }
            $stmt->close();
            $this->saveAlbum($albums[$i]);
        }
        return $albums;
    }

    public function saveAlbum(Album $album)
    {
        $sql = "INSERT INTO Album (alid, alname, aid, aldate, alimgurl) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->prepare($sql);
        $alid = $album->getAlid();
        $name = $album->getName();
        $aid = $album->getAid();
        $aldate = $album->getDate();
        $imgurl = $album->getImgurl();
        $stmt->bind_param('sssss', $alid, $name, $aid, $aldate, $imgurl);
        $stmt->execute();
        $stmt->close();


        for ($i = 0; $i < count($album->getTracks()); $i++) {
            $track = $album->getTracks()[$i];
            $this->saveTrack($alid, $track, $i);
        }
    }

    public function saveTrack($alid, Track $track, $aorder)
    {
        $tid = $track->getTid();
        $tname = $track->getName();
        $duration = $track->getDuration();
        $tscore = $track->getScore();
        $url = $track->getUrl();

        $sql = "INSERT INTO Track (tid, tname, duration, tscore, url) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('sssss', $tid, $tname, $duration, $tscore, $url);
        $stmt->execute();
        $stmt->close();

        $sql = "INSERT INTO AlbumTrack (alid, tid) VALUES (?, ?)";
        if ($stmt = $this->prepare($sql)) {
            $stmt->bind_param("ss", $alid, $tid);
            $stmt->execute();
            $stmt->close();
        }
        //TODO can't insert order.
    }

    public function History($method, $username, $tid = null, $alid = null, $pid = null, $htime)
    {
        if ($method == 'POST') {
            $sql = "INSERT INTO History(username, tid, alid, pid, htime) VALUE (?, ?, ?, ?, ?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('sssss', $username, $tid, $alid, $pid, $htime);
        } else {
            $sql = "DELETE FROM History WHERE username = ? AND htime = ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('ss', $username, $htime);
        }
        $stmt->execute();
        $stmt->close();
    }

    public function getArtist($user, $aid)
    {
        $artist = array();
        $sql = "SELECT aname, imgurl, latime FROM Artist LEFT OUTER JOIN LikeArtist L ON Artist.aid = L.aid AND L.username = ? WHERE Artist.aid = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('ss', $user, $aid);
        $stmt->execute();
        $stmt->bind_result($aname, $imgurl, $username);
        $builder = new ArtistBuilder();
        while ($stmt->fetch()) {
            $builder->setId($aid)->setName($aname)->setImgurl($imgurl)->setLike($username);
            $stmt->close();
        }
        $genre = $this->getGenre($aid);
        array_push($artist, $builder->setGenere($genre)->build());

        return $artist;
    }

    public function getGenre($aid)
    {
        $genre = array();
        $sql = "SELECT agenre FROM ArtistGenere WHERE aid = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('s', $aid);
        $stmt->execute();
        $stmt->bind_result($agenre);
        while ($stmt->fetch()) {
            array_push($genre, $agenre);
        }
        $stmt->close();
        return $genre;
    }

    public function getAlbum($user, $alid)
    {
        $album = array();
        $sql = "SELECT aname, alname, aldate, alimgurl, laltime, Album.aid FROM Album INNER JOIN Artist A ON Album.aid = A.aid LEFT OUTER JOIN LikeAlbum L ON Album.alid = L.alid AND L.username = ? WHERE Album.alid = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('ss', $user, $alid);
        $stmt->execute();
        $stmt->bind_result($aname, $alname, $aldate, $alimgurl, $laltime, $aid);
        $builder = new AlbumBuilder();
        while ($stmt->fetch()) {
            $builder->setAname($aname)->setName($alname)->setDate($aldate)->setImgurl($alimgurl)->setLike($laltime)->setAid($aid);
        }
        $stmt->close();
        array_push($album, $builder->setTracks($this->getAlbumTracks($alid, $aname))->build());
        return $album;
    }

    public function getAlbumTracks($alid, $aname)
    {
        $tracks = array();
        $sql = "SELECT tid, tname, duration, tscore, url, aid FROM Album NATURAL JOIN AlbumTrack NATURAL JOIN Track WHERE alid = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('s', $alid);
        $stmt->execute();
        $stmt->bind_result($tid, $tname, $duration, $tscore, $url, $aid);
        $builder = new TrackBuilder();
        while ($stmt->fetch()) {
            $track = $builder->setTid($tid)->setName($tname)->setDuration($duration)->setScore($tscore)->setUrl($url)->setAname($aname)->setAid($aid)->build();
            array_push($tracks, $track);
        }
        $stmt->close();
        return $tracks;
    }

    public function Playlist($method, $pid = null, $username = null, $access = null, $pname = null, $htime = null)
    {
        if ($method == 'POST') {
            $sql = "INSERT INTO Playlist(access, username, pname, ptime) VALUE (?, ?, ?, ?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('ssss', $access, $username, $pname, $htime);
            $stmt->execute();
            $stmt->close();
        } else {
            $sql = "DELETE FROM PlaylistTrack WHERE pid = ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('s', $pid);
            $stmt->execute();
            $stmt->close();
            $sql = "DELETE FROM LikePlaylist WHERE pid = ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('s', $pid);
            $stmt->execute();
            $stmt->close();
            $sql = "DELETE FROM History WHERE pid = ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('s', $pid);
            $stmt->execute();
            $stmt->close();
            $sql = "DELETE FROM Playlist WHERE pid = ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('s', $pid);
            $stmt->execute();
            $stmt->close();
        }
    }


//    public function UpdatePlaylist($pid, $pname, $access)
//    {
//        $sql = "UPDATE Playlist SET pname = ?, access = ? WHERE pid = ?";
//        $stmt = $this->prepare($sql);
//        $stmt->bind_param('sss', $pname, $access, $pid);
//        $stmt->execute();
//        $stmt->close();
//    }

    public function PlaylistTrack($method, $pid, $tid)
    {
        if ($method == 'POST') {
            $sql = "INSERT INTO PlaylistTrack(pid, tid) VALUE (?, ?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('ss', $pid, $tid);
        } else {
            $sql = "DELETE FROM PlaylistTrack WHERE pid = ? AND tid = ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('ss', $pid, $tid);
        }
        $stmt->execute();
        $stmt->close();
    }

    public function updateProfile($username, $name, $email, $city)
    {
        $sql = "UPDATE User SET uname = ?, email = ?, city = ? WHERE username = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('ssss', $name, $email, $city, $username);
        if ($stmt->execute() === TRUE) {
            $stmt->close();
            return "OK";
        } else {
            $stmt->close();
            return "ERROR";
        }
    }

    public function register($user)
    {
        $username = $user["username"];
        $name = $user["name"];
        $password = $user["password"];
        $email = $user["email"];
        $city = $user["city"];
        $imguri = "";
        $sql = "SELECT username FROM User WHERE username= ?;";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->close();
            return "EXIST";
        } else {
            $stmt->close();
            $sql = "INSERT INTO User (username, uname, password, email, city, imguri) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('ssssss', $username, $name, $password, $email, $city, $imguri);
            if ($stmt->execute() === TRUE) {
                $stmt->close();
                return "OK";
            } else {
                $stmt->close();
                return "ERROR";
            }
        }
    }

    public function rateSong($username, $tid, $rscore)
    {
        $rtime = date('Y-m-d H:i:s');
        $sql = "SELECT rtime FROM RateSong WHERE username = ? AND tid = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('ss', $username, $tid);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {//表示曾经评价过
            $stmt->close();
            $sql = "UPDATE RateSong SET rscore = ? WHERE username = ? AND tid = ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('sss', $rscore, $username, $tid);
            $stmt->execute();
            $stmt->close();
        } else {//没评价过
            $stmt->close();
            $sql = "INSERT INTO RateSong(username, tid, rscore, rtime) VALUES (?, ?, ?, ?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('ssss', $username, $tid, $rscore, $rtime);
            $stmt->execute();
            $stmt->close();
        }
        $sql = "UPDATE Track SET tscore = (SELECT AVG(rscore) FROM RateSong WHERE tid = ?) WHERE tid = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('ss', $tid, $tid);
        $stmt->execute();
        $stmt->close();
    }

    public function follow($method, $username1, $username2)
    {
        if ($method == 'POST') {
            $ftime = date('Y-m-d H:i:s');
            $sql = "INSERT INTO Follow(username1, username2, ftime) VALUES (?, ?, ?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('sss', $username1, $username2, $ftime);
        } else {
            $sql = "DELETE FROM Follow WHERE username1 = ? AND username2 = ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('ss', $username1, $username2);
        }
        $stmt->execute();
        $stmt->close();
    }

    public function likeArtist($method, $username, $aid)
    {
        if ($method == 'POST') {
            $latime = date('Y-m-d H:i:s');
            $sql = "INSERT INTO LikeArtist(username, aid, latime) VALUES (?, ?, ?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('sss', $username, $aid, $latime);
        } else {
            $sql = "DELETE FROM LikeArtist WHERE username = ? AND aid = ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('ss', $username, $aid);
        }
        $stmt->execute();
        $stmt->close();
    }

    public function likeAlbum($method, $username, $alid)
    {
        if ($method == 'POST') {
            $laltime = date('Y-m-d H:i:s');
            $sql = "INSERT INTO LikeAlbum(username, alid, laltime) VALUES (?, ?, ?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('sss', $username, $alid, $laltime);
        } else {
            $sql = "DELETE FROM LikeAlbum WHERE username = ? AND alid = ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('ss', $username, $alid);
        }
        $stmt->execute();
        $stmt->close();
    }

    public function likePlaylist($method, $username, $pid)
    {
        if ($method == 'POST') {
            $lptime = date('Y-m-d H:i:s');
            $sql = "INSERT INTO LikePlaylist(username, pid, lptime) VALUES (?, ?, ?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('sss', $username, $pid, $lptime);
        } else {
            $sql = "DELETE FROM LikePlaylist WHERE username = ? AND pid = ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param('ss', $username, $pid);
        }
        $stmt->execute();
        $stmt->close();
    }


    public function getLikedArtist($username)
    {
        $artists = array();
        $sql = "SELECT aid, aname, imgurl FROM LikeArtist NATURAL JOIN Artist WHERE username = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($aid, $aname, $imgurl);
        while ($stmt->fetch()) {
            $builder = new ArtistBuilder();
            array_push($artists, $builder->setId($aid)->setName($aname)->setImgurl($imgurl)->build());
        }
        $stmt->close();
        return $artists;
    }

    public function getLikedAlbum($username)
    {
        $albums = array();
        $sql = "SELECT alid, alname, aid, aname, alimgurl, aldate FROM LikeAlbum NATURAL JOIN Album NATURAL JOIN Artist WHERE username = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($alid, $alname, $aid, $aname, $alimgurl, $aldate);
        while ($stmt->fetch()) {
            $builder = new AlbumBuilder();
            array_push($albums, $builder->setAlid($alid)->setName($alname)->setAid($aid)->setAname($aname)->setImgurl($alimgurl)->setDate($aldate)->build());
        }
        $stmt->close();
        return $albums;
    }

    public function getLikedPlaylist($username)
    {
        $playlists = array();
        $sql = "SELECT Playlist.pid, Playlist.pname, Playlist.username, Playlist.pimguri,Playlist.ptime FROM LikePlaylist INNER JOIN Playlist ON LikePlaylist.pid = Playlist.pid WHERE LikePlaylist.username = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($pid, $pname, $username, $pimguri, $ptime);
        while ($stmt->fetch()) {
            $builder = new PlaylistBuilder();
            array_push($playlists, $builder->setId($pid)->setName($pname)->setUsername($username)->setUri($pimguri)->setDate($ptime)->build());
        }
        $stmt->close();
        return $playlists;
    }

    public function getLikedUser($username)
    {
        $usersid = array();
        $user = array();
        $sql = "SELECT username2 FROM Follow WHERE username1 = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($username2);
        while ($stmt->fetch()) {
            array_push($usersid, $username2);
        }
        for ($i = 0; $i < count($usersid); $i++) {
            array_push($user, $this->getUser($username,"me", $usersid[$i]));
        }
        $stmt->close();
        return $user;
    }

    public function getUser($user, $type, $username)
    {
//        $user = array();
        $builder = new UserBuilder();
        $sql = "SELECT username, uname, city, email, imguri, ftime FROM User LEFT OUTER JOIN Follow F ON F.username1 = ? AND F.username2 = ? WHERE user.username = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('sss', $user, $username, $username);
        $stmt->execute();
        $stmt->bind_result($username, $uname, $city, $email, $imguri, $ftime);
        while ($stmt->fetch()) {
            $builder->setUsername($username)->setName($uname)->setCity($city)->setEmail($email)->setImguri($imguri)->setLike($ftime);
        }
        $stmt->close();
        if ($type == "me") {
            return $builder->build();
        } else {
            $playlist = array();
            $sql = "SELECT pid, pname, pimguri, ptime FROM Playlist WHERE username = ? AND access = ?";
            $stmt = $this->prepare($sql);
            $access = "public";
            $stmt->bind_param('ss', $username, $access);
            $stmt->execute();
            $stmt->bind_result($pid, $pname, $pimguri, $ptime);
            while ($stmt->fetch()) {
                $listbuilder = new PlaylistBuilder();
                array_push($playlist, $listbuilder->setId($pid)->setUsername($username)->setName($pname)->setUri($pimguri)->setDate($ptime)->build());
            }
            $stmt->close();
//            $user = array_merge($user, );
            return $builder->setPlaylist($playlist)->build();
        }
    }


    public function getPlaylistByuser($username)
    {
        $playlist = array();
        $sql = "SELECT pname, pid, ptime, pimguri FROM Playlist WHERE username = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($pname, $pid, $ptime, $pimguri);
        while ($stmt->fetch()) {
            $builder = new PlaylistBuilder();
            $builder->setId($pid)->setName($pname)->setUsername($username)->setDate($ptime)->setUri($pimguri);
            array_push($playlist, $builder->build());
        }
        $stmt->close();
        return $playlist;
    }

    public function getPlaylistByid($user, $pid)
    {
        $playlist = array();
        $sql = "SELECT pname, Playlist.username, ptime, pimguri, lptime, access FROM Playlist LEFT OUTER JOIN LikePlaylist L ON Playlist.pid = L.pid AND L.username = ? WHERE Playlist.pid = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('ss', $user, $pid);
        $stmt->execute();
        $stmt->bind_result($pname, $username, $ptime, $pimguri, $lptime, $access);
        $builder = new PlaylistBuilder();
        while ($stmt->fetch()) {
            $builder->setId($pid)->setName($pname)->setUsername($username)->setDate($ptime)->setUri($pimguri)->setLike($lptime)->setAccess($access);
            $stmt->close();
            array_push($playlist, $builder->setTracks($this->getPlaylistTracks($pid))->build());
        }
        return $playlist;
    }

    public function getPlaylistTracks($pid)
    {
        $tracks = array();
        $sql = "SELECT tid, tname, alname, aname, duration, tscore, url, Album.alid FROM PlaylistTrack NATURAL JOIN Track NATURAL JOIN AlbumTrack NATURAL JOIN Album NATURAL JOIN Artist WHERE pid = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('s', $pid);
        $stmt->execute();
        $stmt->bind_result($tid, $tname, $alname, $aname, $duration, $tscore, $url, $alid);
        $builder = new TrackBuilder();
        while ($stmt->fetch()) {
            $track = $builder->setTid($tid)->setName($tname)->setAlname($alname)->setAname($aname)->setDuration($duration)->setScore($tscore)->setUrl($url)->setAlid($alid)->build();
            array_push($tracks, $track);
        }
        $stmt->close();
        return $tracks;
    }



    public function getHistory($username)
    {
        $history = array();
        $sql = "SELECT tid, tname, alname, aname, duration, tscore, url, htime, Album.alid FROM History NATURAL JOIN Track NATURAL JOIN AlbumTrack NATURAL JOIN Album NATURAL JOIN Artist WHERE username = ? ORDER BY History.htime";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($tid, $tname, $alname, $aname, $duration, $tscore, $url, $htime, $alid);
        $builder = new TrackBuilder();
        while ($stmt->fetch()) {
            $track = $builder->setTid($tid)->setName($tname)->setAlname($alname)->setAname($aname)->setDuration($duration)->setScore($tscore)->setUrl($url)->setHtime($htime)->setAlid($alid)->build();
            array_push($history, $track);
        }
        $stmt->close();
        return $history;
    }

    public function getAllPlaylistName($username)
    {
        $names = array();
        $sql = "SELECT pid, pname FROM Playlist WHERE username = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($pid, $pname);
        while ($stmt->fetch()) {
            $builder = new PlaylistBuilder();
            $builder->setId($pid)->setName($pname);
            array_push($names, $builder->build());
        }
        $stmt->close();
        return $names;
    }



}