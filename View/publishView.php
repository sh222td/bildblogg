<?php

require_once('./Model/loginModel.php');
require_once('./View/memberView.php');

class PublishView {

    private $loginModel;
    private $activeUser;
    private $fileName;
    private $name;
    private $message;

    public function __construct() {
        $this->loginModel = new LoginModel();
    }
    public function didUserPressLogoutButton() {
        if (isset($_POST['logoutButton'])) {
            return true;
        }
    }

    public function didUserPressReturnButton() {
        if (isset($_POST['returnButton'])) {
            return true;
        }
    }

    public function uploadFile() {
        if(isset($_GET['post'])) {
            return true;
        } else {
            return false;
        }
    }

    public function setFileName() {
        if (isset($_FILES['file']['name'])) {
            $this->name = $_FILES['file']['name'];
            $this->fileName = $_FILES['file']['tmp_name'];
        }
    }

    public function getName() {
        if (isset($_FILES['file']['name'])) {
            $this->name = $_FILES['file']['name'];
            return $this->name;
        }
    }

    public function getFileName() {
        if (isset($_FILES['file']['name'])) {
            $this->fileName = $_FILES['file']['tmp_name'];
            return $this->fileName;
        }
    }

    public function getFileDescription() {
        if (isset($_POST['description'])) {
            return $_POST['description'];
        }
    }

    public function getRadioButtonValue() {
        if(isset($_POST['category'])) {
            return $_POST['category'];
        }
    }

    public function getChmodValue() {
        if(isset($_POST['chmod'])) {
            return $_POST['chmod'];
        }
    }

    public function checkFile() {
        $imageFileType = pathinfo($this->name,PATHINFO_EXTENSION);
        if ($imageFileType != "jpg" && $imageFileType != "JPG" && $imageFileType != "jpeg" && $imageFileType != "JPEG" && $imageFileType != "png" && $imageFileType != "PNG" && $imageFileType != "gif" && $imageFileType != "GIF") {
            return false;
        } else {
            return true;
        }
    }

    public function errorFileMessage() {
        return $this->message = "Bilden måste vara av typen jpg, jpeg, png eller gif.";
    }

    public function successFileMessage() {
        return $this->message = "Uppladdningen lyckades!.";
    }

    public function fileAlreadyExistsMessage() {
        return $this->message = "Filen existerar redan, välj en annan bild.";
    }

    public function publishView() {
        $this->activeUser = $_SESSION['login'];
        $ret ="<div id = 'memberView' >
                    <div id ='headerBox'>
                        <form method='POST' action='?logout'>
                        <input id='logoutButton' type='submit' name='logoutButton' value='Logga ut'>
                        </form>
                        <h1 id = 'mainTitleLoggedIn'>Bildblogg</h1>
                        <form method='POST' action='?main'>
                        <div id = 'uploadBox'>
                            <input id='uploadButton' type='submit' name='returnButton' value='Tillbaka till startsida'>
                        </div>
                        </form>
                    </div>
                    <form method='POST' action ='?post' enctype='multipart/form-data'>
                        <div id = 'article'>
                            <p id='errorMSG'>$this->message</p>
                            <input id ='file' type='file' name='file' ><br>
                            <p>Ange bildbeskrivning:</p>
                            <textarea id='imgDescription' rows='7' cols='48' name='description'></textarea><br>
                            <input type='radio' name='category' value='1'>Memes
                            <input type='radio' name='category' value='2'>Natur
                            <input type='radio' name='category' value='3'>Mat
                            <input type='radio' name='category' value='4' checked>Övrigt
                            <p>Ange rättigheter:</p>
                            <input type='radio' name='chmod' value='678' checked>678
                            <input type='radio' name='chmod' value='766'>766
                            <br><input id='submit' type='submit' name='submit' value='Publicera inlägg'>
                        </div>
                    </form>
                </div>";
        return $ret;
    }
}