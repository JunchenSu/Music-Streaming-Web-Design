(function () {
    var searchBtn = $('search');

    var username;
    var historyButton = $("historyButton");
    var main2 = $("mainmain2");
    var newpl = $("newplform");
    var favoriteBtn = $("favorite");
    var profileBtn = $("profilebtn");
    var sumbitBtn = $("sumbit");
    var pb = $("playlistBtn");
    var newplBtn = $("newpl");
    var plsubmit = $("plsubmit");
    var logoutBtn = $("logout");

    function init() {
        getUsername(checkLogin);
        getUsername(startPage);

        searchBtn.addEventListener('click', search);
        historyButton.addEventListener('click', function () {
            getUsername(historyPage);
        });
        favoriteBtn.addEventListener('click', function () {
            getUsername(favoritePage);
        });
        profileBtn.addEventListener('click', function () {
            getUsername(showProfile);
        });
        sumbitBtn.addEventListener('click', function () {
            getUsername(showProfile);
        });
        pb.addEventListener('click', function () {
            getUsername(myPlaylist);
        });
        newplBtn.addEventListener('click', newPlaylist);
        plsubmit.addEventListener('click', checkPlaylistName);
        logoutBtn.addEventListener('click', logOut)
    }

    function logOut() {
        ajax('GET', '../PHP/rpc/LogOut.php', null, function (res) {
            alert("Logout Successful");
            window.location.href="../index.html"
        }, function () {
        });
    }
    function checkLogin(username) {
        if (username === "Invalid") {
            window.location.href="../index.html"
        }
    }


    function checkPlaylistName() {
        var name = $("plname").value;
        var url = '../PHP/rpc/PlaylistPage.php?id=' + username + "&name=" + name;
        ajax('POST', url, null, function (res) {
            var info = $("error");
            if (res === "OK") {
                info.style.color = '#00AA00';
                info.innerHTML = "Create Success"
            } else {
                info.style.color = '#cc0000';
                info.innerHTML = "You have already created a playlist with the same username"
            }
            showElement(info);
        }, function () {
        });
    }

    function newPlaylist() {
        hideElement(main2);
        $("userid").value = username;
        var main = $("mainmain");
        main.innerHTML = '';
        showElement(newpl);
    }

    function myPlaylist(userid) {
        hideElement(main2);
        hideElement(newpl);
        var main = $("mainmain");
        main.innerHTML = "";
        var main_grids = $("div", {
            className: "main-grids"
        });
        main.appendChild(main_grids);
        var url = '../PHP/rpc/MyPlaylist.php?userid=' + userid;
        ajax('GET', url, null, function (res) {
            var data = JSON.parse(res);
            var playlists = data.playlists;

            if (playlists.length === 0) {
                var album_section = createSection();
                var album_row0 = createRow("No Favorite Playlist");
                album_section.append(album_row0);
                main_grids.appendChild(album_section);
            } else {
                var album_section = createSection();
                for (var i = 0; i < playlists.length; i++) {
                    if (i % 4 === 0) {
                        if (i === 0) {
                            var album_row0 = createRow("My Playlists");
                        }
                        else {
                            album_row0.appendChild(clearfix());
                            album_section.append(album_row0);
                            var album_row0 = createRow("");
                        }
                    }
                    album_row0.appendChild(createPlaylist(playlists[i], "me"));
                    if (i === playlists.length - 1) {
                        album_row0.appendChild(clearfix());
                        album_section.append(album_row0);
                    }
                }
                main_grids.appendChild(album_section);
            }
        }, function () {
        });
    }

    function showProfile(userid) {
        hideElement(newpl);
        var main = $("mainmain");
        main.innerHTML = '';
        var url = '../PHP/rpc/UserProfile.php?id=' + userid + '&type=me';
        ajax('GET', url, null, function (res) {
            var data = JSON.parse(res);
            var user = data.user;
            $("username").value = userid;
            $("name").value = user.name;
            $("email").value = user.email;
            $("city").value = user.city;
            var icon = $("icon");
            if (user.imguri === "" || user.imguri === null) {
                icon.src = "../css/images/default.png"
            } else {
                icon.src= user.imguri + "?" + "t=" + Math.random();
            }
        }, function () {
        });
        showElement(main2);
    }

    function favoritePage(userid) {
        hideElement(main2);
        hideElement(newpl);
        var main = $("mainmain");
        main.innerHTML = "";
        var main_grids = $("div", {
            className: "main-grids"
        });
        showLoadingMessage(main_grids, 'Loading');
        main.appendChild(main_grids);
        var url = '../PHP/rpc/FavoritePage.php?id=' + userid;
        ajax('GET', url, null, function (res) {
            var data = JSON.parse(res);
            var albums = data.album;
            var artists = data.artist;
            var playlists = data.playlist;
            var user = data.user;

            if (albums.length === 0) {
                var album_section = createSection();
                var album_row0 = createRow("No Favorite Album");
                album_section.append(album_row0);
                main_grids.innerHTML = "";
                main_grids.appendChild(album_section);
            } else {
                var album_section = createSection();
                for (var i = 0; i < albums.length; i++) {
                    if (i % 4 === 0) {
                        if (i === 0) {
                            var album_row0 = createRow("Your Favorite Album");
                        }
                        else {
                            album_row0.appendChild(clearfix());
                            album_section.append(album_row0);
                            var album_row0 = createRow("");
                        }
                    }
                    album_row0.appendChild(createAlbum(albums[i]));
                    if (i === albums.length - 1) {
                        album_row0.appendChild(clearfix());
                        album_section.append(album_row0);
                    }
                }
                main_grids.innerHTML = "";
                main_grids.appendChild(album_section);
            }

            if (artists.length === 0) {
                var album_section = createSection();
                var album_row0 = createRow("No Favorite Artist");
                album_section.append(album_row0);
                main_grids.appendChild(album_section);
            } else {
                var artist_section = createSection();
                for (var i = 0; i < artists.length; i++) {
                    if (i % 4 === 0) {
                        if (i === 0) {
                            var album_row0 = createRow("Your Favorite Artist");
                        }
                        else {
                            album_row0.appendChild(clearfix());
                            album_section.append(album_row0);
                            var album_row0 = createRow("");
                        }
                    }
                    album_row0.appendChild(createArtist(artists[i]));
                    if (i === artists.length - 1) {
                        album_row0.appendChild(clearfix());
                        artist_section.append(album_row0);
                    }
                }
                main_grids.appendChild(artist_section);
            }


            if (playlists.length === 0) {
                var album_section = createSection();
                var album_row0 = createRow("No Favorite Playlist");
                album_section.append(album_row0);
                main_grids.appendChild(album_section);
            } else {
                var album_section = createSection();
                for (var i = 0; i < playlists.length; i++) {
                    if (i % 4 === 0) {
                        if (i === 0) {
                            var album_row0 = createRow("Your Favorite Playlist");
                        }
                        else {
                            album_row0.appendChild(clearfix());
                            album_section.append(album_row0);
                            var album_row0 = createRow("");
                        }
                    }
                    album_row0.appendChild(createPlaylist(playlists[i], "other"));
                    if (i === playlists.length - 1) {
                        album_row0.appendChild(clearfix());
                        album_section.append(album_row0);
                    }
                }
                main_grids.appendChild(album_section);
            }

            if (user.length === 0) {
                var album_section = createSection();
                var album_row0 = createRow("No Favorite User");
                album_section.append(album_row0);
                main_grids.appendChild(album_section);
            } else {
                var artist_section = createSection();
                for (var i = 0; i < user.length; i++) {
                    if (i % 4 === 0) {
                        if (i === 0) {
                            var album_row0 = createRow("Your Favorite User");
                        }
                        else {
                            album_row0.appendChild(clearfix());
                            album_section.append(album_row0);
                            var album_row0 = createRow("");
                        }
                    }
                    album_row0.appendChild(createUser(user[i]));
                    if (i === user.length - 1) {
                        album_row0.appendChild(clearfix());
                        artist_section.append(album_row0);
                    }
                }
                main_grids.appendChild(artist_section);
            }

        }, function () {
        });
    }

    function startPage(userid) {
        hideElement(main2);
        hideElement(newpl);
        username = userid;
        var main = $("mainmain");
        main.innerHTML = "";
        var main_grids = $("div", {
            className: "main-grids"
        });
        showLoadingMessage(main_grids, 'Loading recommandation from Spotify...');
        main.appendChild(main_grids);
        var url = '../PHP/rpc/StartPage.php?username=' + userid;
        ajax('GET', url, null, function (res) {
            var data = JSON.parse(res);
            var albums = data.albums;
            var artists = data.artists;

            if (albums.length === 0) {
                console.log("NONE Album");
            } else {
                var album_section = createSection();
                for (var i = 0; i < albums.length; i++) {
                    if (i % 4 === 0) {
                        if (i === 0) {
                            if (artists.length === 0) {
                                var album_row0 = createRow("New Released Albums");
                            } else {
                                var album_row0 = createRow("Album From Your Favorite Artist");
                            }
                        }
                        else {
                            album_row0.appendChild(clearfix());
                            album_section.append(album_row0);
                            var album_row0 = createRow("");
                        }
                    }
                    album_row0.appendChild(createAlbum(albums[i]));
                    if (i === albums.length - 1) {
                        album_row0.appendChild(clearfix());
                        album_section.append(album_row0);
                    }
                }
                main_grids.innerHTML = "";
                main_grids.appendChild(album_section);
            }

            if (artists.length === 0) {
                console.log("NONE Artist");
            } else {
                var artist_section = createSection();
                for (var i = 0; i < artists.length && i <= 9; i++) {
                    if (i % 4 === 0) {
                        if (i === 0) {
                            var album_row0 = createRow("Related Artist");
                        }
                        else {
                            album_row0.appendChild(clearfix());
                            album_section.append(album_row0);
                            var album_row0 = createRow("");
                        }
                    }
                    album_row0.appendChild(createArtist(artists[i]));
                    if (i === artists.length - 1) {
                        album_row0.appendChild(clearfix());
                        artist_section.append(album_row0);
                    }
                }
                main_grids.appendChild(album_section);
            }
        }, function () {
        });
    }


    function getUsername(func) {
        var url = '../PHP/rpc/SignIn.php';
        ajax('GET', url, null, function (res) {
            func(res);
        }, function () {
        });
    }

    function search() {
        hideElement(main2);
        var main = $("mainmain");
        main.innerHTML = "";
        var main_grids = $("div", {
            className: "main-grids"
        });
        showLoadingMessage(main_grids, 'Loading recommandation from Spotify...');
        main.appendChild(main_grids);
        var keyword = $("keyword").value;
        var type = $("topsearch").value;
        var url = '../PHP/rpc/Search.php?user=' + username + '&keyword=' + keyword + "&type=" + type;
        if (type === "artists") {
            ajax('GET', url, null, function (res) {
                var data = JSON.parse(res);
                var artists = data.artists;
                var artist_section = createSection();
                if (artists.length === 0) {
                    var album_row0 = createRow("No related artists");
                    album_row0.appendChild(clearfix());
                    artist_section.append(album_row0);
                    main_grids.innerHTML = "";
                    main_grids.appendChild(artist_section);
                } else {
                    for (var i = 0; i < artists.length; i++) {
                        if (i % 4 === 0) {
                            if (i === 0) {
                                var album_row0 = createRow("Artist");
                            }
                            else {
                                album_row0.appendChild(clearfix());
                                artist_section.append(album_row0);
                                var album_row0 = createRow("");
                            }
                        }
                        album_row0.appendChild(createArtist(artists[i]));
                        if (i === artists.length - 1) {
                            album_row0.appendChild(clearfix());
                            artist_section.append(album_row0);
                        }
                    }
                    main_grids.innerHTML = "";
                    main_grids.appendChild(artist_section);
                }
            }, function () {
            });
        } else if (type === "albums") {
            ajax('GET', url, null, function (res) {
                var data = JSON.parse(res);
                var albums = data.albums;
                var album_section = createSection();
                if (albums.length === 0) {
                    var album_row0 = createRow("No related albums");
                    album_row0.appendChild(clearfix());
                    album_section.append(album_row0);
                    main_grids.innerHTML = "";
                    main_grids.appendChild(album_section);
                } else {
                    for (var i = 0; i < albums.length; i++) {
                        if (i % 4 === 0) {
                            if (i === 0) {
                                var album_row0 = createRow("Album");
                            }
                            else {
                                album_row0.appendChild(clearfix());
                                album_section.append(album_row0);
                                var album_row0 = createRow("");
                            }
                        }
                        album_row0.appendChild(createAlbum(albums[i]));
                        if (i === albums.length - 1) {
                            album_row0.appendChild(clearfix());
                            album_section.append(album_row0);
                        }
                    }
                    main_grids.innerHTML = "";
                    main_grids.appendChild(album_section);
                }
            }, function () {
            });
        } else if (type === "new") {
            startPage("");
        } else {
            ajax('GET', url, null, function (res) {
                var data = JSON.parse(res);
                console.log(data);
                var playlists = data.playlists;
                var playlists_section = createSection();
                if (playlists.length === 0) {
                    var playlists_row0 = createRow("No related playlists");
                    playlists_section.append(playlists_row0);
                    main_grids.innerHTML = "";
                    main_grids.appendChild(playlists_section);
                } else {
                    for (var i = 0; i < playlists.length; i++) {
                        if (i % 4 === 0) {
                            if (i === 0) {
                                var playlists_row0 = createRow("Playlist");
                            }
                            else {
                                playlists_row0.appendChild(clearfix());
                                playlists_section.append(playlists_row0);
                                var playlists_row0 = createRow("");
                            }
                        }
                        playlists_row0.appendChild(createPlaylist(playlists[i], "other"));
                        if (i === playlists.length - 1) {
                            playlists_row0.appendChild(clearfix());
                            playlists_section.append(playlists_row0);
                        }
                    }
                    main_grids.innerHTML = "";
                    main_grids.appendChild(playlists_section);
                }
            }, function () {
            });
        }
    }

    function createRow(title) {

        if (title === "") {
            return $("div", {
                className: 'recommended-grids'
            });
        } else {
            var grid = $("div", {
                className: 'recommended-grids'
            });

            var info = $("div", {
                className: 'recommended-info'
            });
            var txt = $('h3', {});
            txt.innerHTML = title;
            info.appendChild(txt);
            grid.appendChild(info);
            return grid;
        }
    }

    function clearfix() {
        return $("div", {
            className: "clearfix"
        });
    }


    function createSection() {
        var sectiontmp = $("div", {
            className: 'recommended'
        });
        return sectiontmp;
    }

    function createUser(artist) {
        if (artist.imgurl !== "") {
            var url = artist.imguri;
        } else {
            var url = "../css/images/default.png";
        }
        if (artist.name.length >= 12) {
            var ar = artist.name.split(" ");
            if (ar.length >= 1) {
                anametxt = ar[0] + " " + ar[1];
            } else {
                anametxt = ar[0];
            }
        } else {
            var anametxt = artist.username;
        }


        var albumtmp = $("div", {
            className: 'col-md-3 resent-grid recommended-grid'
        });

        var imgDiv = $("div", {
            className: 'resent-grid-img recommended-grid-img'
        });
        var img = $('img', {
            src: url,
            alt: ""
        });
        var aimg = $("a", {});
        aimg.appendChild(img);
        imgDiv.appendChild(aimg);


        var infoDiv = $("div", {
            className: 'resent-grid-info recommended-grid-info video-info-grid',
        });


        var ul = $("ul", {
            id: "ul"
        });

        var lianame = $("li", {});
        var p1 = $("p", {
            className: 'author author-info'
        });
        var atxt = $("a", {
            className: "author",
            href: "javascript:void(0);"
        });
        atxt.innerHTML = anametxt;
        p1.appendChild(atxt);
        lianame.appendChild(p1);


        ul.appendChild(lianame);

        infoDiv.appendChild(ul);

        albumtmp.appendChild(imgDiv);
        albumtmp.appendChild(infoDiv);

        atxt.onclick = function () {
            userPage(artist.username);
        };

        return albumtmp;
    }


    function createArtist(artist) {
        if (artist.imgurl !== "") {
            var url = artist.imgurl;
        } else {
            var url = "../css/images/default.png";
        }
        if (artist.name.length >= 12) {
            var ar = artist.name.split(" ");
            if (ar.length >= 1) {
                anametxt = ar[0] + " " + ar[1];
            } else {
                anametxt = ar[0];
            }
        } else {
            var anametxt = artist.name;
        }


        var albumtmp = $("div", {
            className: 'col-md-3 resent-grid recommended-grid'
        });

        var imgDiv = $("div", {
            className: 'resent-grid-img recommended-grid-img'
        });
        var img = $('img', {
            src: url,
            alt: ""
        });
        var aimg = $("a", {});
        aimg.appendChild(img);
        imgDiv.appendChild(aimg);


        var infoDiv = $("div", {
            className: 'resent-grid-info recommended-grid-info video-info-grid',
        });


        var ul = $("ul", {
            id: "ul"
        });

        var lianame = $("li", {});
        var p1 = $("p", {
            className: 'author author-info'
        });
        var atxt = $("a", {
            className: "author",
            href: "javascript:void(0);"
        });
        atxt.innerHTML = anametxt;
        p1.appendChild(atxt);
        lianame.appendChild(p1);


        ul.appendChild(lianame);

        infoDiv.appendChild(ul);

        albumtmp.appendChild(imgDiv);
        albumtmp.appendChild(infoDiv);

        atxt.onclick = function () {
            artistPage(artist.id);
        };

        return albumtmp;
    }

    function createPlaylist(album, type) {

        var url = album.imgurl;
        if (url === null) {
            url = "../css/images/pldefault.png";
        }
        var alnametxt = album.name;
        var anametxt = album.username;
        var datetxt = album.date.split(" ")[0];
        var id = album.id;

        var albumtmp = $("div", {
            className: 'col-md-3 resent-grid recommended-grid'
        });

        var imgDiv = $("div", {
            className: 'resent-grid-img recommended-grid-img'
        });
        var img = $('img', {
            src: url,
            alt: ""
        });
        var aimg = $("a", {});
        aimg.appendChild(img);
        imgDiv.appendChild(aimg);


        var infoDiv = $("div", {
            className: 'resent-grid-info recommended-grid-info video-info-grid',
        });

        var alname = $("h5", {});
        var h5txt = $("a", {
            className: "title",
        });
        h5txt.innerHTML = alnametxt;
        alname.appendChild(h5txt);

        var ul = $("ul", {});

        var lianame = $("li", {});
        var p1 = $("p", {
            className: 'author author-info'
        });
        var atxt = $("a", {
            className: "author",
            href: "javascript:void(0);"
        });
        atxt.innerHTML = anametxt;
        p1.appendChild(atxt);
        lianame.appendChild(p1);

        var lidate = $("li", {
            className: "right-list"
        });
        var p2 = $("p", {
            className: "views views-info"
        });
        p2.innerHTML = datetxt;

        lidate.appendChild(p2);

        ul.appendChild(lianame);
        ul.appendChild(lidate);


        infoDiv.appendChild(alname);
        infoDiv.appendChild(ul);

        albumtmp.appendChild(imgDiv);
        albumtmp.appendChild(infoDiv);

        if (type === "me"){
            alname.onclick = function () {
                myPlaylistPage(id);
            };
        } else {
            alname.onclick = function () {
                playlistPage(id);
            };
        }

        lianame.onclick = function () {
            userPage(anametxt);
        };

        return albumtmp;
    }

    function createAlbum(album) {
        var url = album.imgurl;
        var alnametxt = album.name;
        var anametxt = album.aname;
        var datetxt = album.date.split(" ")[0];
        var id = album.alid;
        var aid = album.aid;

        var albumtmp = $("div", {
            className: 'col-md-3 resent-grid recommended-grid'
        });

        var imgDiv = $("div", {
            className: 'resent-grid-img recommended-grid-img'
        });
        var img = $('img', {
            src: url,
            alt: ""
        });
        var aimg = $("a", {});
        aimg.appendChild(img);
        imgDiv.appendChild(aimg);


        var infoDiv = $("div", {
            className: 'resent-grid-info recommended-grid-info video-info-grid',
        });

        var alname = $("h5", {});
        var h5txt = $("a", {
            className: "title",
            href: "javascript:void(0);"
        });
        h5txt.innerHTML = alnametxt;
        alname.appendChild(h5txt);

        var ul = $("ul", {});

        var lianame = $("li", {});
        var p1 = $("p", {
            className: 'author author-info'
        });
        var atxt = $("a", {
            className: "author",
            href: "javascript:void(0);"
        });
        atxt.innerHTML = anametxt;
        p1.appendChild(atxt);
        lianame.appendChild(p1);

        var lidate = $("li", {
            className: "right-list"
        });
        var p2 = $("p", {
            className: "views views-info"
        });
        p2.innerHTML = datetxt;

        lidate.appendChild(p2);

        ul.appendChild(lianame);
        ul.appendChild(lidate);


        infoDiv.appendChild(alname);
        infoDiv.appendChild(ul);

        albumtmp.appendChild(imgDiv);
        albumtmp.appendChild(infoDiv);

        alname.onclick = function () {
            albumPage(id);
        };
        atxt.onclick = function () {
            artistPage(aid);
        };

        return albumtmp;
    }

    function historyPage(username) {
        hideElement(main2);
        hideElement(newpl);
        $('mainmain').innerHTML = "";
        var url = '../PHP/rpc/HistoryPage.php?id=' + username;
        ajax('GET', url, null, function (res) {
            var allInOne = JSON.parse(res);
            var history = allInOne.history;

            var showTopGrids = $('div', {
                className: "show-top-grids"
            });

            var albumPageMainGrid = $('div', {
                className: "main-grids news-main-grids"
            });

            var albumTrackInfo = $('div', {
                className: "recommended-info"
            });

            albumTrackInfo.innerHTML = "";
            var hisTitle = $('h2', {});
            hisTitle.innerText = "Recently Played";
            albumTrackInfo.appendChild(hisTitle);
            var albumName = $('h2', {});
            albumName.innerText = "History";
            for (var i = 0; i < history.length; i++) {
                buildHistoryPage(addTracksInHistory, albumTrackInfo, history[i], i);
            }
            setTimeout("", 10000);
            albumPageMainGrid.appendChild(albumTrackInfo);
            showTopGrids.appendChild(albumPageMainGrid);
            var mainmain = $("mainmain");
            mainmain.innerHTML = "";
            mainmain.appendChild(showTopGrids);
        }, function () {
            alert("no Result found");
        });
    }

    function buildHistoryPage(func, albumTrackInfo, track, i) {
        var url = '../PHP/rpc/Playlist.php?username=' + username;
        ajax('GET', url, null, function (res) {
            var res1 = JSON.parse(res).playlists;
            func(res1, albumTrackInfo, track, i);
        }, function () {
        });
    }

    function addTracksInHistory(playlists, albumTrackInfo, track, i) {

        // var buttonStyle = $('span', {
        //     className: "glyphicon glyphicon-play"
        // });
        // var playButton = $('button', {
        //     id: "play-button",
        //     type: "button",
        //     className: "btn btn-default btn-sm"
        // });
        // var play = $('a', {
        //     href: track.url
        // });
        // play.innerText = "Play";
        //
        // playButton.appendChild(play);
        // playButton.appendChild(buttonStyle);

        var trackName = $('a', {
            href: track.url
        });
        trackName.innerText = (i + 1) + ". " + track.name;
        var artistName = $('p', {});
        artistName.innerText = track.aname;
        var duration = $('p', {});
        duration.innerText = track.duration;

        var addTo = $('select', {});
        var defaultpp = $('option', {});
        defaultpp.innerText = "Choose..";
        addTo.appendChild(defaultpp);

        for (var j = 0; j < playlists.length; j++) {
            var pp = $('option', {
                value: playlists[j].id
            });
            pp.innerText = playlists[j].name;
            addTo.appendChild(pp);
        }

        addTo.addEventListener('change', function () {
            var data = {"pid": this.value, "tid": track.tid};
            var input = JSON.stringify(data);
            ajax('POST', "../PHP/rpc/Playlist.php", input, function (res) {
                console.log(input);
                console.log(res);
            }, function () {
            });
        });

        var addToFrame = $('p', {});
        addToFrame.innerText = "Add to: ";
        addToFrame.appendChild(addTo);

        var deleteFrame = $('h6', {});
        var deleteButton = $('a', {
            style : "cursor: pointer"
        });
        deleteButton.innerText = "Delete";
        deleteFrame.appendChild(deleteButton);

        deleteButton.onclick =  function () {

            var data = {"username": username, "htime": track.htime};
            var input = JSON.stringify(data);
            ajax('DELETE', "../PHP/rpc/History.php", input, function (res) {
                console.log(input);
                console.log(res);

            }, function () {
            });

            hideElement(deleteButton);
            var er = $('p', {
                style: "color:#00AA00"
            });
            er.innerText = "Deleted";
            deleteFrame.appendChild(er);
        };

        var author = $('p', {
            href : "#",
            style : "cursor: pointer"
        });
        author.innerText = "From: " + track.alname;
        author.onclick = function () {
            albumPage(track.alid);
        };

        var albumPageInfoContent = $('h5', {});
        albumPageInfoContent.appendChild(trackName);
        albumPageInfoContent.appendChild(author);
        // albumPageInfoContent.appendChild(playButton);
        albumPageInfoContent.appendChild(addToFrame);
        albumPageInfoContent.appendChild(deleteFrame);


        var albumPageInfo = $('div', {
            id: "album-page-info",
            className: "col-md-11 history-right"
        });
        albumPageInfo.innerHTML = "";
        albumPageInfo.appendChild(albumPageInfoContent);

        var star0 = $('i', {
            id: "rate-star",
            className: "fa fa-star"
        });
        var star1 = $('i', {
            className: "fa fa-star"
        });
        var star2 = $('i', {
            className: "fa fa-star"
        });
        var star3 = $('i', {
            className: "fa fa-star"
        });
        var star4 = $('i', {
            className: "fa fa-star"
        });
        var stars = [star0, star1, star2, star3, star4];

        var score = $('p', {});
        if (parseInt(track.score) === 0) {
            score.appendChild(star0);
        }
        else {
            for (var i = 0; i < parseInt(track.score); i++) {
                score.appendChild(stars[i]);
            }
        }

        var clearFix = $('div', {
            className: "clearFix"
        });

        var albumPageGrid = $('div', {
            id: "album-page-grid",
            className: "history-grids"
        });

        var op0 = $('option', {});
        op0.innerText = "..";

        var op1 = $('option', {
            value: '1'
        });
        op1.innerText = "1";

        var op2 = $('option', {
            value: '2'
        });
        op2.innerText = "2";

        var op3 = $('option', {
            value: '3'
        });
        op3.innerText = "3";

        var op4 = $('option', {
            value: '4'
        });
        op4.innerText = "4";

        var op5 = $('option', {
            value: '5'
        });
        op5.innerText = "5";

        var select = $('select', {});
        select.appendChild(op0);
        select.appendChild(op1);
        select.appendChild(op2);
        select.appendChild(op3);
        select.appendChild(op4);
        select.appendChild(op5);
        var rater = $('p', {
            id: "rater"
        });
        select.addEventListener('change', function () {
            var data = {"username": username, "tid": track.tid, "score": this.value};
            var input = JSON.stringify(data);
            ajax('POST', "../PHP/rpc/RateSong.php", input, function (res) {
                console.log(input);
                console.log(res);

            }, function () {
            });
            hideElement(rater);
        });
        rater.innerText = "Rate: ";
        rater.appendChild(select);


        var rightSide = $('div', {});
        rightSide.appendChild(score);
        rightSide.appendChild(rater);
        rightSide.appendChild(duration);
        // rightSide.appendChild(author);


        albumPageGrid.appendChild(albumPageInfo);
        albumPageGrid.appendChild(rightSide);
        albumPageGrid.appendChild(clearFix);

        albumTrackInfo.appendChild(albumPageGrid);
    }

    function myPlaylistPage(pid) {
        hideElement(main2);
        hideElement(newpl);
        $('mainmain').innerHTML = "";
        var url = '../PHP/rpc/PlaylistPage.php?id=' + pid + "&user=" + username;
        ajax('GET', url, null, function (res) {
            var allInOne = JSON.parse(res);
            var playlists = allInOne.playlists;
            var playlist = playlists[0];
            var tracks = playlist.tracks;
            var showTopGrids = $('div', {
                className: "show-top-grids"
            });

            var albumPageMainGrid = $('div', {
                className: "main-grids news-main-grids"
            });

            var albumTrackInfo = $('div', {
                className: "recommended-info"
            });

            albumTrackInfo.innerHTML = "";
            var albumName = $('h2', {
                id: "theFirst"
            });
            albumName.innerText = playlist.name;
            var albumTime = $('h4', {});
            albumTime.innerText = playlist.date;
            var albumPicUrl = $('img', {
                src: playlist.imgurl
            });
            albumPicUrl.style.width = '300px';
            albumPicUrl.style.height = '300px';
            var albumPic = $('div', {
                align: "center"
            });


            var del;
            var delButton = $('i', {
                className: 'glyphicon glyphicon-trash',
                id: "heart"
            });
            del = $('div', {
                id: "theSecond",
                className: "fav-link"
            });
            del.appendChild(delButton);
            del.onclick = function () {
                deletePlaylist(pid, playlist.imgurl);
            };

            albumPic.appendChild(albumPicUrl);
            albumTrackInfo.appendChild(albumName);
            albumTrackInfo.appendChild(del);
            albumTrackInfo.appendChild(albumTime);
            albumTrackInfo.appendChild(albumPic);


            for (var i = 0; i < tracks.length; i++) {
                addTracksInPlaylistPage(pid, albumTrackInfo, tracks[i], i, "me");
            }
            albumPageMainGrid.appendChild(albumTrackInfo);
            showTopGrids.appendChild(albumPageMainGrid);
            var mainmain = $("mainmain");
            mainmain.innerHTML = "";
            mainmain.appendChild(showTopGrids);
        }, function () {
            alert("no Result found");
        });
    }

    function deletePlaylist(pid, uri) {
        var url = '../PHP/rpc/Playlist.php?';
        var data = JSON.stringify({"access": "del", "pid": pid, "uri" : uri});
        console.log(url);
        ajax('DELETE', url, data, function (res) {
            alert("Delete succeed");
            myPlaylist(username);
        }, function () {
        });
    }

    function playlistPage(pid) {
        console.log("not me");
        hideElement(main2);
        hideElement(newpl);
        $('mainmain').innerHTML = "";
        var url = '../PHP/rpc/PlaylistPage.php?id=' + pid + "&user=" + username;
        ajax('GET', url, null, function (res) {
            var allInOne = JSON.parse(res);
            var playlists = allInOne.playlists;
            var playlist = playlists[0];
            var tracks = playlist.tracks;
            var showTopGrids = $('div', {
                className: "show-top-grids"
            });

            var albumPageMainGrid = $('div', {
                className: "main-grids news-main-grids"
            });

            var albumTrackInfo = $('div', {
                className: "recommended-info"
            });

            albumTrackInfo.innerHTML = "";
            var albumName = $('h2', {
                id: "theFirst"
            });
            albumName.innerText = playlist.name;
            var albumTime = $('h4', {});
            albumTime.innerText = playlist.date;
            var albumPicUrl = $('img', {
                src: playlist.imgurl
            });
            albumPicUrl.style.width = '300px';
            albumPicUrl.style.height = '300px';
            var albumPic = $('div', {
                align: "center"
            });


            var like;
            var likeButton;
            var beLiked = playlist.like;
            if (beLiked === null) {
                likeButton = $('i', {
                    className: 'fa fa-heart-o',
                    id: "heart"
                });
                like = $('div', {
                    id: "theSecond",
                    className: "fav-link"
                });
                likeButton.dataset.favorite = 'false';
            }
            else {
                likeButton = $('i', {
                    className: 'fa fa-heart',
                    id: "heart"
                });
                like = $('div', {
                    id: "theSecond",
                    className: "fav-link"
                });
                likeButton.dataset.favorite = 'true';
            }
            like.appendChild(likeButton);
            like.onclick = function () {
                if (likeButton.dataset.favorite === 'false') {
                    likeButton.className = 'fa fa-heart';
                    IlikeIt(pid, 1, "Playlist");
                    likeButton.dataset.favorite = 'true';
                } else {
                    likeButton.className = 'fa fa-heart-o';
                    IlikeIt(pid, 2, "Playlist");
                    likeButton.dataset.favorite = 'false';
                }
            };

            albumPic.appendChild(albumPicUrl);

            albumTrackInfo.appendChild(albumName);
            albumTrackInfo.appendChild(like);
            albumTrackInfo.appendChild(albumTime);
            albumTrackInfo.appendChild(albumPic);


            for (var i = 0; i < tracks.length; i++) {
                addTracksInPlaylistPage(pid, albumTrackInfo, tracks[i], i, "other");
            }
            albumPageMainGrid.appendChild(albumTrackInfo);
            showTopGrids.appendChild(albumPageMainGrid);
            var mainmain = $("mainmain");
            mainmain.innerHTML = "";
            mainmain.appendChild(showTopGrids);
        }, function () {
            alert("no Result found");
        });
    }




    function addTracksInPlaylistPage(pid, albumTrackInfo, track, i, type) {

        // var buttonStyle = $('span', {
        //     className: "glyphicon glyphicon-play"
        // });
        // var playButton = $('button', {
        //     id: "play-button",
        //     type: "button",
        //     className: "btn btn-default btn-sm"
        // });
        // var play = $('a', {
        //     href: track.url
        // });
        // play.innerText = "Play";
        //
        // playButton.appendChild(play);
        // playButton.appendChild(buttonStyle);

        var trackName = $('a', {
            href: track.url
        });
        trackName.innerText = (i + 1) + ". " + track.name + " ";
        trackName.onclick = function () {
            addHistory(track.tid, track.alid, pid);
        };
        var artistName = $('p', {
            style : "cursor: pointer"
        });
        artistName.innerText = track.alname;
        artistName.onclick = function () { albumPage(track.alid); }
        var duration = $('p', {});
        duration.innerText = track.duration;

        var albumPageInfoContent = $('h5', {});

        albumPageInfoContent.appendChild(trackName);
        // albumPageInfoContent.appendChild(playButton);
        albumPageInfoContent.appendChild(artistName);
        albumPageInfoContent.appendChild(duration);


        if (type === "me") {
            var deleteFrame = $('h6', {});
            var deleteButton = $('a', {});
            deleteButton.innerText = "Delete";
            deleteFrame.appendChild(deleteButton);
            albumPageInfoContent.appendChild(deleteFrame);
            deleteButton.onclick = function () {

                var data = {"pid": pid, "tid": track.tid};
                var input = JSON.stringify(data);
                ajax('DELETE', "../PHP/rpc/Playlist.php", input, function (res) {

                }, function () {
                });
                // playlistPage();
                albumPageGrid.innerHTML = "";
                var er = $('p', {
                    style: "color:#00AA00"
                });
                er.innerText = "Deleted";
                deleteFrame.appendChild(er);
            };
        }


        var albumPageInfo = $('div', {
            id: "album-page-info",
            className: "col-md-11 history-right"
        });
        albumPageInfo.innerHTML = "";
        albumPageInfo.appendChild(albumPageInfoContent);

        var star0 = $('i', {
            id: "rate-star",
            className: "fa fa-star"
        });
        var star1 = $('i', {
            className: "fa fa-star"
        });
        var star2 = $('i', {
            className: "fa fa-star"
        });
        var star3 = $('i', {
            className: "fa fa-star"
        });
        var star4 = $('i', {
            className: "fa fa-star"
        });
        var stars = [star0, star1, star2, star3, star4];
        var score = $('p', {});
        if (parseInt(track.score) === 0) {
            score.appendChild(star0);
        }
        else {
            for (var i = 0; i < parseInt(track.score); i++) {
                score.appendChild(stars[i]);
            }
        }

        var clearFix = $('div', {
            className: "clearFix"
        });

        var albumPageGrid = $('div', {
            id: "album-page-grid",
            className: "history-grids"
        });

        var op0 = $('option', {});
        op0.innerText = "..";

        var op1 = $('option', {
            value: '1'
        });
        op1.innerText = "1";

        var op2 = $('option', {
            value: '2'
        });
        op2.innerText = "2";

        var op3 = $('option', {
            value: '3'
        });
        op3.innerText = "3";

        var op4 = $('option', {
            value: '4'
        });
        op4.innerText = "4";

        var op5 = $('option', {
            value: '5'
        });
        op5.innerText = "5";

        var select = $('select', {});
        select.appendChild(op0);
        select.appendChild(op1);
        select.appendChild(op2);
        select.appendChild(op3);
        select.appendChild(op4);
        select.appendChild(op5);
        var rater = $('p', {
            id: "rater"
        });
        select.addEventListener('change', function () {
            var data = {"username": username, "tid": track.tid, "score": this.value};
            var input = JSON.stringify(data);
            ajax('POST', "../PHP/rpc/RateSong.php", input, function (res) {

            }, function () {
            });
            hideElement(rater);
        });
        rater.innerText = "Rate: ";
        rater.appendChild(select);

        var rightSide = $('div', {});
        rightSide.appendChild(rater);
        rightSide.appendChild(duration);

        albumPageGrid.appendChild(albumPageInfo);
        albumPageGrid.appendChild(score);
        albumPageGrid.appendChild(rightSide);
        albumPageGrid.appendChild(clearFix);
        albumTrackInfo.appendChild(albumPageGrid);
    }


    function artistPage(aid) {
        hideElement(main2);
        var mainmian = $('mainmain');
        mainmian.innerHTML = "";
        var url = '../PHP/rpc/ArtistPage.php?id=' + aid + "&user=" + username;
        showLoadingMessage(mainmian, 'Loading data from Spotify...');
        ajax('GET', url, null, function (res) {
            mainmian.innerHTML = "";
            var allInOne = JSON.parse(res);
            var albums = allInOne.albums;

            var artist = allInOne.artists[0];
            var showTopGrids = $('div', {
                className: "show-top-grids"
            });

            var albumPageMainGrid = $('div', {
                className: "main-grids news-main-grids"
            });

            var albumTrackInfo = $('div', {
                className: "recommended-info"
            });

            albumTrackInfo.innerHTML = "";
            var albumName = $('h2', {
                id: "theFirst"
            });
            albumName.innerText = artist.name;
            var albumTime = $('h4', {});
            albumTime.innerText = "genre: " + artist.genere[0] + ", " + artist.genere[1] + ", " + artist.genere[2] + ", " + artist.genere[3];
            var albumPicUrl = $('img', {
                src: artist.imgurl
            });
            albumPicUrl.style.width = '300px';
            albumPicUrl.style.height = '300px';
            var albumPic = $('div', {
                align: "center"
            });

            var like;
            var likeButton;

            var beLiked = artist.like;
            if (beLiked === null) {
                likeButton = $('i', {
                    className: 'fa fa-heart-o',
                    id: "heart"
                });
                like = $('div', {
                    id: "theSecond",
                    className: "fav-link"
                });
                likeButton.dataset.favorite = 'false';
            }
            else {
                likeButton = $('i', {
                    className: 'fa fa-heart',
                    id: "heart"
                });
                like = $('div', {
                    id: "theSecond",
                    className: "fav-link"
                });
                likeButton.dataset.favorite = 'true';
            }
            like.appendChild(likeButton);
            like.onclick = function () {
                if (likeButton.dataset.favorite === 'false') {
                    likeButton.className = 'fa fa-heart';
                    IlikeIt(aid, 1, "Artist");
                    likeButton.dataset.favorite = 'true';
                } else {
                    likeButton.className = 'fa fa-heart-o';
                    IlikeIt(aid, 2, "Artist");
                    likeButton.dataset.favorite = 'false';
                }
            };


            albumPic.appendChild(albumPicUrl);
            albumTrackInfo.appendChild(albumName);
            albumTrackInfo.appendChild(like);
            albumTrackInfo.appendChild(albumTime);
            albumTrackInfo.appendChild(albumPic);
            albumPageMainGrid.appendChild(albumTrackInfo);
            showTopGrids.appendChild(albumPageMainGrid);
            $('mainmain').appendChild(showTopGrids);


            if (albums.length === 0) {
                var album_section = createSection();
                var album_row0 = createRow("No albums found");
                album_section.append(album_row0);
                albumTrackInfo.appendChild(album_section);
            } else {
                var album_section = createSection();
                for (var i = 0; i < albums.length; i++) {
                    if (i % 4 === 0) {
                        if (i === 0) {
                            var album_row0 = createRow("Artist's top album");
                        }
                        else {
                            album_row0.appendChild(clearfix());
                            album_section.append(album_row0);
                            var album_row0 = createRow("");
                        }
                    }
                    album_row0.appendChild(createAlbum(albums[i]));
                    if (i === albums.length - 1) {
                        album_row0.appendChild(clearfix());
                        album_section.append(album_row0);
                    }
                }
            }
            albumTrackInfo.appendChild(album_section);

        }, function () {
        });
    }

    function IlikeIt(id, type, from) {
        var method = 'POST';
        if (type === 2) method = 'Delete';
        var url = "../PHP/rpc/Like" + from + ".php?username=" + username + "&id=" + id;
        ajax(method, url, null, function (res) {
        }, function () {
            alert("something wrong with like API!");
        });
    }



    function albumPage(albumid) {
        hideElement(main2);
        $('mainmain').innerHTML = "";
        var url = '../PHP/rpc/AlbumPage.php?id=' + albumid + "&user=" + username;
        ajax('GET', url, null, function (res) {
            var allInOne = JSON.parse(res);
            var albums = allInOne.albums;
            var album = albums[0];
            var tracks = album.tracks;
            var showTopGrids = $('div', {
                className: "show-top-grids"
            });

            var albumPageMainGrid = $('div', {
                className: "main-grids news-main-grids"
            });

            var albumTrackInfo = $('div', {
                className: "recommended-info"
            });

            albumTrackInfo.innerHTML = "";
            var albumName = $('h2', {
                id: "theFirst"
            });
            albumName.innerText = album.name;

            var albumTime = $('h4', {});
            albumTime.innerText = "Release Date: " + album.date.split(" ")[0];
            var albumPicUrl = $('img', {
                src: album.imgurl
            });
            albumPicUrl.style.width = '320px';
            albumPicUrl.style.height = '320px';
            var albumPic = $('div', {
                align: "center"
            });
            var like;
            var likeButton;


            var beLiked = album.like;
            if (beLiked === null) {
                likeButton = $('i', {
                    className: 'fa fa-heart-o',
                    id: "heart"
                });
                like = $('div', {
                    id: "theSecond",
                    className: "fav-link"
                });
                likeButton.dataset.favorite = 'false';
            }
            else {
                likeButton = $('i', {
                    className: 'fa fa-heart',
                    id: "heart"
                });
                like = $('div', {
                    id: "theSecond",
                    className: "fav-link"
                });
                likeButton.dataset.favorite = 'true';
            }
            like.appendChild(likeButton);
            likeButton.onclick = function () {
                if (likeButton.dataset.favorite === 'false') {
                    likeButton.className = 'fa fa-heart';
                    IlikeIt(albumid, 1, "Album");
                    likeButton.dataset.favorite = 'true';
                } else {
                    likeButton.className = 'fa fa-heart-o';
                    IlikeIt(albumid, 2, "Album");
                    likeButton.dataset.favorite = 'false';
                }
            };

            albumPic.appendChild(albumPicUrl);
            albumTrackInfo.appendChild(albumName);
            albumTrackInfo.appendChild(like);
            albumTrackInfo.appendChild(albumTime);
            albumTrackInfo.appendChild(albumPic);


            if (tracks.length === 0) {
                alert("This album don't include any track.")
            } else {
                for (var i = 0; i < tracks.length; i++) {
                    buildAlbumPage(addTracksInAlbumPage, albumTrackInfo, tracks[i], i, albumid);
                }
            }
            albumPageMainGrid.appendChild(albumTrackInfo);
            showTopGrids.appendChild(albumPageMainGrid);
            var mainmain = $("mainmain");
            mainmain.innerHTML = "";
            mainmain.appendChild(showTopGrids);
        }, function () {

        });
    }

    function buildAlbumPage(func, albumTrackInfo, track, i, alid) {
        var url = '../PHP/rpc/Playlist.php?username=' + username;
        ajax('GET', url, null, function (res) {
            var res1 = JSON.parse(res).playlists;

            func(res1, albumTrackInfo, track, i, alid);
        }, function () {
        });
    }


    function addTracksInAlbumPage(playlists, albumTrackInfo, track, i, alid) {

        // var buttonStyle = $('span', {
        //     className: "glyphicon glyphicon-play"
        // });
        // var playButton = $('button', {
        //     id: "play-button",
        //     type: "button",
        //     className: "btn btn-default btn-sm"
        // });
        // var play = $('a', {
        //     href: track.url
        // });
        // play.innerText = "Play";
        //
        // playButton.appendChild(play);
        // playButton.appendChild(buttonStyle);

        var trackName = $('a', {
            href: track.url
        });
        trackName.innerText = (i + 1) + ". " + track.name;
        trackName.onclick = function () {
            addHistory(track.tid, alid, '');
        };
        var artistName = $('p', {
            style : "cursor: pointer"
        });
        artistName.innerText = track.aname;
        artistName.onclick = function () {
            artistPage(track.aid);
        };
        var duration = $('p', {});
        duration.innerText =  track.duration;

        var addTo = $('select', {});
        var defaultpp = $('option', {});
        defaultpp.innerText = "Choose..";
        addTo.appendChild(defaultpp);
        for (var j = 0; j < playlists.length; j++) {
            var pp = $('option', {
                value: playlists[j].id
            });
            pp.innerText = playlists[j].name;
            addTo.appendChild(pp);
        }

        addTo.addEventListener('change', function () {
            var data = {"pid": this.value, "tid": track.tid};
            var input = JSON.stringify(data);
            ajax('POST', "../PHP/rpc/Playlist.php", input, function (res) {
            }, function () {
            });
        });


        var addToFrame = $('p', {});
        addToFrame.innerText = "Add to: ";
        addToFrame.appendChild(addTo);

        var albumPageInfoContent = $('h5', {});
        albumPageInfoContent.appendChild(trackName);
        // albumPageInfoContent.appendChild(playButton);
        albumPageInfoContent.appendChild(artistName);
        albumPageInfoContent.appendChild(duration);
        albumPageInfoContent.appendChild(addToFrame);

        var albumPageInfo = $('div', {
            id: "album-page-info",
            className: "col-md-11 history-right"
        });
        albumPageInfo.innerHTML = "";
        albumPageInfo.appendChild(albumPageInfoContent);

        var star0 = $('i', {
            id: "rate-star",
            className: "fa fa-star"
        });
        var star1 = $('i', {
            className: "fa fa-star"
        });
        var star2 = $('i', {
            className: "fa fa-star"
        });
        var star3 = $('i', {
            className: "fa fa-star"
        });
        var star4 = $('i', {
            className: "fa fa-star"
        });
        var stars = [star0, star1, star2, star3, star4];
        var score = $('p', {});
        if (parseInt(track.score) == 0) {
            score.appendChild(star0);
        }
        else {
            for (var i = 0; i < parseInt(track.score); i++) {
                score.appendChild(stars[i]);
            }
        }

        var clearFix = $('div', {
            className: "clearFix"
        });

        var albumPageGrid = $('div', {
            id: "album-page-grid",
            className: "history-grids"
        });

        var op0 = $('option', {});
        op0.innerText = "..";

        var op1 = $('option', {
            value: '1'
        });
        op1.innerText = "1";

        var op2 = $('option', {
            value: '2'
        });
        op2.innerText = "2";

        var op3 = $('option', {
            value: '3'
        });
        op3.innerText = "3";

        var op4 = $('option', {
            value: '4'
        });
        op4.innerText = "4";

        var op5 = $('option', {
            value: '5'
        });
        op5.innerText = "5";

        var select = $('select', {});
        select.appendChild(op0);
        select.appendChild(op1);
        select.appendChild(op2);
        select.appendChild(op3);
        select.appendChild(op4);
        select.appendChild(op5);
        var rater = $('p', {
            id: "rater"
        });
        select.addEventListener('change', function () {
            var data = {"username": username, "tid": track.tid, "score": this.value};
            var input = JSON.stringify(data);
            ajax('POST', "../PHP/rpc/RateSong.php", input, function (res) {

            }, function () {
            });
            hideElement(rater);
        });
        rater.innerText = "Rate: ";
        rater.appendChild(select);

        var rightSide = $('div', {});
        rightSide.appendChild(rater);
        rightSide.appendChild(duration);

        albumPageGrid.appendChild(albumPageInfo);
        albumPageGrid.appendChild(score);
        albumPageGrid.appendChild(rightSide);
        albumPageGrid.appendChild(clearFix);

        albumTrackInfo.appendChild(albumPageGrid);
    }

    function userPage(aid) {
        hideElement(main2);
        var mainmian = $('mainmain');
        mainmian.innerHTML = "";
        var url = '../PHP/rpc/UserProfile.php?id=' + aid + "&type=other" + "&user=" + username;
        showLoadingMessage(mainmian, 'Loading');
        ajax('GET', url, null, function (res) {
            mainmian.innerHTML = "";
            var artist = JSON.parse(res).user;
            var albums = artist.playlist;

            var showTopGrids = $('div', {
                className: "show-top-grids"
            });

            var albumPageMainGrid = $('div', {
                className: "main-grids news-main-grids"
            });

            var albumTrackInfo = $('div', {
                className: "recommended-info"
            });

            albumTrackInfo.innerHTML = "";
            var albumName = $('h2', {
                id: "theFirst"
            });
            albumName.innerText = artist.username;
            var albumTime = $('h4', {});
            albumTime.innerText = artist.city;
            var albumPicUrl = $('img', {
                src: artist.imguri
            });
            albumPicUrl.style.width = '300px';
            albumPicUrl.style.height = '300px';
            var albumPic = $('div', {
                align: "center"
            });

            var like;
            var likeButton;

            var beLiked = artist.like;
            if (beLiked === null) {
                likeButton = $('i', {
                    className: 'fa fa-heart-o',
                    id: "heart"
                });
                like = $('div', {
                    id: "theSecond",
                    className: "fav-link"
                });
                likeButton.dataset.favorite = 'false';
            }
            else {
                likeButton = $('i', {
                    className: 'fa fa-heart',
                    id: "heart"
                });
                like = $('div', {
                    id: "theSecond",
                    className: "fav-link"
                });
                likeButton.dataset.favorite = 'true';
            }
            like.appendChild(likeButton);
            like.onclick = function () {
                if (likeButton.dataset.favorite === 'false') {
                    likeButton.className = 'fa fa-heart';
                    follow(username, aid, "POST");
                    likeButton.dataset.favorite = 'true';
                } else {
                    likeButton.className = 'fa fa-heart-o';
                    follow(username, aid, "DELETE");
                    likeButton.dataset.favorite = 'false';
                }
            };


            albumPic.appendChild(albumPicUrl);
            albumTrackInfo.appendChild(albumName);
            albumTrackInfo.appendChild(like);
            albumTrackInfo.appendChild(albumTime);
            albumTrackInfo.appendChild(albumPic);
            albumPageMainGrid.appendChild(albumTrackInfo);
            showTopGrids.appendChild(albumPageMainGrid);
            $('mainmain').appendChild(showTopGrids);


            if (albums.length === 0) {
                var album_section = createSection();
                var album_row0 = createRow("This user hasn't created any playlists");
                album_section.append(album_row0);
                albumTrackInfo.appendChild(album_section);
            } else {
                var album_section = createSection();
                for (var i = 0; i < albums.length; i++) {
                    if (i % 4 === 0) {
                        if (i === 0) {
                            var album_row0 = createRow("Public playlist");
                        }
                        else {
                            album_row0.appendChild(clearfix());
                            album_section.append(album_row0);
                            var album_row0 = createRow("");
                        }
                    }
                    if (aid === username) {
                        album_row0.appendChild(createPlaylist(albums[i], "me"));
                    } else {
                        album_row0.appendChild(createPlaylist(albums[i], "other"));
                    }

                    if (i === albums.length - 1) {
                        album_row0.appendChild(clearfix());
                        album_section.append(album_row0);
                    }
                }
            }
            albumTrackInfo.appendChild(album_section);

        }, function () {
        });
    }

    function follow(id1, id2, method) {
        var url = "../PHP/rpc/Follow.php?user1=" + id1 + "&user2=" + id2;
        ajax(method, url, null, function (res) {
        }, function () {
            console.log("something wrong with follow API!");
        });
    }

    function addHistory(tid, alid, pid) {
        var url = '../PHP/rpc/History.php';
        if (pid === "") {
            var data = {"username": username, "tid": tid, "alid": alid};
        } else {
            var data = {"username": username, "tid": tid, "alid": alid, "pid": pid};
        }
        var input = JSON.stringify(data);
        ajax('POST', url, input, function (res) {
        }, function () {
        });
    }


    function $(tag, options) {
        if (!options) {
            return document.getElementById(tag);
        }

        var element = document.createElement(tag);

        for (var option in options) {
            if (options.hasOwnProperty(option)) {
                element[option] = options[option];
            }
        }

        return element;
    }


    function ajax(method, url, data, callback, errorHandler) {
        var xhr = new XMLHttpRequest();

        xhr.open(method, url, true);

        xhr.onload = function () {
            switch (xhr.status) {
                case 200:
                    callback(xhr.responseText);
                    break;
            }
        };

        xhr.onerror = function () {
            console.error("The request couldn't be completed.");
            errorHandler();
        };

        if (data === null) {
            xhr.send();
        } else {
            xhr.setRequestHeader("Content-Type",
                "application/json;charset=utf-8");
            xhr.send(data);
        }
    }

    function hideElement(element) {
        element.style.display = 'none';
    }

    function showElement(element, style) {
        var displayStyle = style ? style : 'block';
        element.style.display = displayStyle;
    }

    function showLoadingMessage(maingrids, msg) {
        maingrids.innerHTML = '<p align="center"><i class="fa fa-spinner fa-spin"></i> '
            + msg + '</p>';
    }


    init();
})();