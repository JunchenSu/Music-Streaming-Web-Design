<?php include_once "./DBConnection.php";
/**
 * Created by PhpStorm.
 * User: HaoYu
 * Date: 2017/12/5
 * Time: 20:27
 */
$link = new mysqli("localhost:3306", "root", "root", "test");
$sql = "CREATE TABLE `User` (
    `username` VARCHAR(45) NOT NULL,
    `uname` VARCHAR(45) NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(200) NULL,
    `city` VARCHAR(45) NULL,
    `imguri` VARCHAR(255) NULL,
    PRIMARY KEY (`username`)
);";
$link->query($sql);
$sql = "CREATE TABLE `Artist` (
    `aid` VARCHAR(45) NOT NULL,
    `aname` VARCHAR(45) NULL,
    `imgurl` VARCHAR(255) NULL,
    PRIMARY KEY (`aid`)
);";
$link->query($sql);
$sql = "CREATE TABLE `ArtistGenere` (
    `aid` VARCHAR(45) NOT NULL,
    `agenre` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`aid`, `agenre`)
);";
$link->query($sql);
$sql = "CREATE TABLE `LikeArtist` (
    `username` VARCHAR(45) NOT NULL,
    `aid` VARCHAR(45) NOT NULL,
    `latime` DATETIME NOT NULL,
    PRIMARY KEY (`username`,`aid`),
    FOREIGN KEY (`username`) REFERENCES `User` (`username`),
    FOREIGN KEY (`aid`) REFERENCES `Artist` (`aid`)
);";
$link->query($sql);
$sql = "CREATE TABLE `Album` (
    `alid` VARCHAR(45) NOT NULL,
    `alname` VARCHAR(80) NOT NULL,
    `aid` VARCHAR(45) NOT NULL,
    `aldate` DATETIME NOT NULL,
    `alimgurl` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`alid`),
    FOREIGN KEY (`aid`) REFERENCES `Artist` (`aid`)
    );";
$link->query($sql);
$sql = "CREATE TABLE `Track` (
    `tid` VARCHAR(45) NOT NULL,
    `tname` VARCHAR(45) NOT NULL,
    `duration` VARCHAR(10) NOT NULL,
    `tscore` VARCHAR(5) NOT NULL,
    `url` VARCHAR(225) NULL,
    PRIMARY KEY (`tid`)
);";
$link->query($sql);
$sql = "CREATE TABLE `AlbumTrack` (
    `alid` VARCHAR(45) NOT NULL,
    `tid` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`alid`,`tid`),
    FOREIGN KEY (`alid`) REFERENCES `Album` (`alid`),
    FOREIGN KEY (`tid`) REFERENCES `Track` (`tid`)
);";
$link->query($sql);
$sql = "CREATE TABLE `Playlist` (
    `pid` INT NOT NULL AUTO_INCREMENT,
    `access` VARCHAR(10) NOT NULL,
    `username` VARCHAR(45) NOT NULL,
    `pname` VARCHAR(45) NOT NULL,
    `ptime` DATETIME NOT NULL,
    `pimguri` VARCHAR(255) NULL,
    PRIMARY KEY (`pid`),
    FOREIGN KEY (`username`) REFERENCES `User` (`username`)
);";
$link->query($sql);
$sql = "CREATE TABLE `PlaylistTrack` (
    `pid` INT NOT NULL,
    `tid` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`pid`,`tid`),
    FOREIGN KEY (`pid`) REFERENCES `Playlist` (`pid`),
    FOREIGN KEY (`tid`) REFERENCES `Track` (`tid`)
);";
$link->query($sql);
$sql = "CREATE TABLE `Follow` (
    `username1` VARCHAR(45) NOT NULL,
    `username2` VARCHAR(45) NOT NULL,
    `ftime` DATETIME NOT NULL,
    PRIMARY KEY (`username1`, `username2`),
    FOREIGN KEY (`username1`) REFERENCES `User` (`username`),
    FOREIGN KEY (`username2`) REFERENCES `User` (`username`)
);";
$link->query($sql);
$sql = "CREATE TABLE `RateSong` (
    `username` VARCHAR(45) NOT NULL,
    `tid` VARCHAR(45) NOT NULL,
    `rscore` VARCHAR(5) NOT NULL,
    `rtime` DATETIME NOT NULL,
    PRIMARY KEY (`username`, `tid`),
    FOREIGN KEY (`username`) REFERENCES `User` (`username`),
    FOREIGN KEY (`tid`) REFERENCES `Track` (`tid`)
);";
$link->query($sql);
$sql = "CREATE TABLE `LikeAlbum` (
    `username` VARCHAR(45) NOT NULL,
    `alid` VARCHAR(45) NOT NULL,
    `laltime` DATETIME NOT NULL,
    PRIMARY KEY (`username`,`alid`),
    FOREIGN KEY (`username`) REFERENCES `User` (`username`),
    FOREIGN KEY (`alid`) REFERENCES `Album` (`alid`)
);";
$link->query($sql);
$sql = "CREATE TABLE `LikePlaylist` (
    `username` VARCHAR(45) NOT NULL,
    `pid` INT NOT NULL,
    `lptime` DATETIME NOT NULL,
    PRIMARY KEY (`username`,`pid`),
    FOREIGN KEY (`username`) REFERENCES `User` (`username`),
    FOREIGN KEY (`pid`) REFERENCES `Playlist` (`pid`)
);";
$link->query($sql);
$sql = "CREATE TABLE `History` (
    `username` VARCHAR(45) NOT NULL,
    `tid` VARCHAR(45) NOT NULL,
    `alid` VARCHAR(45) NOT NULL,
    `pid` INT NULL,
    `htime` DATETIME NOT NULL,
    PRIMARY KEY (`username`,`htime`),
    FOREIGN KEY (`username`) REFERENCES `User` (`username`),
    FOREIGN KEY (`tid`) REFERENCES `Track` (`tid`),
    FOREIGN KEY (`alid`) REFERENCES `Album` (`alid`),
    FOREIGN KEY (`pid`) REFERENCES `Playlist` (`pid`)
);";
$link->query($sql);