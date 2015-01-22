<?php
require_once('./properties.php');

class LoginModel {

    private $username;
    private $password;
    private $properties;
    private $errorMSG = "";

    public function __construct() {
        $this->properties = new Properties();
    }

    public function setSession() {
        $_SESSION['login'] = true;
    }

    public function checkSession() {
        if (isset($_SESSION['login'])) {
            return $_SESSION['login'];
        } false;
    }

    public function killSession() {
        unset($_SESSION['login']);
        return true;
    }

    //Checks if a valid user exists in the db.
    public function checkLogin($username, $password){
        $this->username = strip_tags($username);
        $this->password = strip_tags($password);

        $this->controlInput();

        $db = $this->properties->dbReader();

        $user = mysqli_query($db, "SELECT * FROM users WHERE username='$username' AND password='$password'");
        $row = mysqli_fetch_array($user);

        if ($row > 0) {
            $_SESSION['login'] = $row['username'];
            return $_SESSION['login'];
        } else {
            return false;
        }
    }

    //Control what input has been written.
    public function controlInput() {
        if ($this->username == "") {
            $this->errorMSG = "Fyll i användarnamn";
            return true;
        }
        else if ($this->password == "") {
            $this->errorMSG = "Fyll i lösenord";
            return true;
        }
        else if (preg_match('/[^a-z0-9]/i', $this->username) || (preg_match('/[^a-z0-9]/i', $this->password))) {
            $this->errorMSG = "Otillåtna karaktärer, endast Aa-Öö, 0-9 är tillåtna.";
            return true;
        }
        else if ($this->username != isset($_SESSION['login']) || $this->password != isset($_SESSION['login'])) {
            $this->errorMSG = "Felaktiga inloggningsuppgifter";
            return true;
        }
    }

    public function getErrorMessage() {
        return $this->errorMSG;
    }
}
