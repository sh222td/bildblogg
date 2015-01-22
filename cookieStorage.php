<?php
//require_once('View/loginView.php');

class CookieStorage {

    private $loginView;

    public function __contruct() {
        $this->loginView = new loginView();
    }

    //Create a cookie.
    public function createCookie() {
        if (isset($_POST["loginKeeper"])) {
            setcookie('username', $_POST['username'], time()+3600);
        }
    }

    //Checks if the cookie values are set.
    public function checkCookie() {
        if (isset($_COOKIE['username'])) {
            return true;
        }
    }

    //Unset the existing cookie.
    public function unsetCookie() {
            setcookie('username', isset($_POST['username']), time()-3600);
    }
}