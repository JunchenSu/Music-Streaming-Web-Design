(function () {
    var signup = $('signup');
    var loginbtn = $('button1');
    var signupbtn = $('button2');
    var signupform = $('switch');
    var border = $('border');
    var error = $('error');

    function init() {
        loginbtn.addEventListener('click', varify);
        signupbtn.addEventListener('click', register);
        signupform.addEventListener('click', switchform)
    }

    function register() {
        var username = $('username').value;
        var password = $('password').value;
        var uname = $('name').value;
        var email = $('email').value;
        var city = $('city').value;
        var regpws = new RegExp(/^(?![^a-zA-Z]+$)(?!\D+$)/);
        var resemail = new RegExp(/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/);
        if (username === null || password === null || name === null || email === null || city === null) {
            showError(0);
        } else if (password.length < 6) {
            showError(1);
        } else if (!regpws.test(password)) {
            showError(2);
        } else if (!resemail.test(email)) {
            showError(3)
        } else {
            password = md5(username + md5(password));
            var url = '../PHP/rpc/SignUp.php';
            var data = JSON.stringify({"username": username, "password": password, "name":uname ,"email":email, "city":city});
            ajax('POST', url, data, function (res) {
                res = "OK";
                if(res === 'OK') {
                    border.style.cssText = 'background: url(./css/login/login_m_bg.png) no-repeat;' +
                        'height: 302 px;' +
                        'overflow: hidden;';
                    showElement(loginbtn);
                    showElement(signupform);
                    hideElement(signupbtn);
                    hideElement(signup);
                    showElement(error);
                    error.style.color = '#00AA00';
                    error.innerText = 'Registration Success';
                } else if(res === 'EXIST') {
                    showElement(error);
                    error.innerHTML = 'Username has been taken.';
                } else {
                    showElement(error);
                    error.innerHTML = 'Registration fail. Please try again';
                }
            }, function () {
            });
        }
    }

    function switchform() {
        hideElement(loginbtn);
        hideElement(signupform);
        border.style.cssText = 'background: url(../css/login/login_m_bg.png) no-repeat;' +
            'height: 550px;' +
            'overflow: hidden;'+
            'background-size: 403px 565px;';
        showElement(signupbtn);
        showElement(signup);
    }

    function varify() {
        var username = $('username').value;
        var password = $('password').value;
        password = md5(username + md5(password));
        var url = '../PHP/rpc/SignIn.php';
        var data = JSON.stringify({"username": username, "password": password});
        ajax('POST', url, data, function (res) {
            if(res === 'OK') {
                window.location.href="../home.html";
            } else {
                showElement(error);
                error.innerHTML = 'Wrong password or username';
            }
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

    function showError(type) {
        showElement(error);
        switch (type) {
            case 0 :
                error.innerHTML = "Please note that all input can't be empty.";
                break;
            case 1 :
                error.innerHTML = "Password must be more than six characters.";
                break;
            case 2 :
                error.innerHTML = "password must contain at least one number and one letter";
                break;
            case 3 :
                error.innerHTML = "Invalid email address";
                break;
        }

    }
    init();
})();