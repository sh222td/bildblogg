<?php

require_once('./Model/loginModel.php');

class LoginView {

    private $loginModel;
    private $errorMSG;
    private $fileErrorMSG;

    public function __construct() {
        $this->loginModel = new loginModel();
        $this->loginMSG = "";
    }

    //Returns a login form view.
    public function show() {
        $ret ="<div id ='headerBox'>
        <h1 id = 'mainTitle'>Bildblogg</h1>
        </div>
        <div id ='loginBox'>
		<h2>Ej inloggad</h2>
		<form method='POST' action='?login'>
			<fieldset>
				<legend></legend>
				<p>$this->errorMSG</p>
				<label id='text' for='UserNameID'>Användarnamn : </label>
				<input id='box' type='text' size='20' name='username'  /><br>
				<label id='text' for='PasswordID'>Lösenord : </label>
				<input id='box' type='password' size='26' name='password'  /><br>
				<label id='text' for='LoginKeeper'>Håll mig inloggad : </label>
				<input type='checkbox' name='loginKeeper' id='checkBox'/><br>
				<input id='sendButton' type='submit' name='sendButton' value='Logga in'>
			</fieldset>
		</form>
		</div>";
        return $ret;
    }

    public function didUserPressLoginButton() {
        if (isset($_POST["sendButton"])) {
            return true;
        }
    }

    public function didUserPressLoginKeeper() {
        if (isset($_POST['loginKeeper'])) {
            return true;
        }
    }

    public function getUserName(){
        if (isset($_POST['username'])) {
            return $_POST['username'];
        }
    }
    public function getPassword() {
        if (isset($_POST['password'])) {
            return $_POST['password'];
        }
    }

    public function didUserPressLoginKeeperButton() {
        if (isset($_POST["loginKeeper"])) {
            return true;
        }
    }

    public function setErrorMessage($errorMSG) {
        $this->errorMSG = $errorMSG;
    }
}