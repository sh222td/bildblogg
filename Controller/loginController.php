<?php

require_once('./Model/loginModel.php');
require_once('./Model/publishModel.php');
require_once('./View/loginView.php');
require_once('./View/HTMLview.php');
require_once('./View/memberView.php');
require_once('./View/publishView.php');
require_once('./cookieStorage.php');

class loginController {

    private $username;
    private $password;
    private $htmlView;
    private $loginView;
    private $memberView;
    private $cookieStorage;
    private $loginModel;
    private $publishModel;
    private $errorMSG;
    private $publishView;

    public function __construct() {
        $this->htmlView = new HTMLView();
        $this->loginView = new LoginView();
        $this->memberView = new memberView();
        $this->loginModel = new LoginModel();
        $this->publishModel = new PublishModel();
        $this->publishView = new PublishView();
        $this->cookieStorage = new CookieStorage();
    }

    //Get the input from the loginView and sends it to the loginModel for control.
    public function checkLogin() {
        $this->username = $this->loginView->getUserName();
        $this->password = $this->loginView->getPassword();
        $this->loginModel->checkLogin($this->username, $this->password);
        $this->errorMessageHandler();
    }

    public function startController() {
        if ($this->memberView->didUserPressPublishButton()) {
            return $this->htmlView->echoHTML($this->publishView->publishView());
        }

        if ($this->memberView->didUserPressLogoutButton()) {
            $this->loginModel->killSession();
            $this->cookieStorage->unsetCookie();
            return $this->htmlView->echoHTML($this->loginView->show());
        }

        if ($this->memberView->didUserPressDeleteCommentButton()) {
            $chosenComment = $this->memberView->didUserPressDeleteCommentButton();
            $this->publishModel->deleteComment($chosenComment);
        }

        //Checks if user want to upload a file then sends the input information to the publishModel for control and uploading.
        if($this->publishView->uploadFile()) {
            $this->publishView->setFileName();
            $fileName = $this->publishView->getName();
            $filePath = $this->publishView->getFileName();
            $imageDescription = $this->publishView->getImageDescription();
            $radioButton = $this->publishView->getRadioButtonValue();
            $chmodValue = $this->publishView->getChmodValue();

            if ($this->publishView->checkFile()) {
                if ($this->publishModel->getFiles($fileName, $filePath, $imageDescription, $radioButton, $chmodValue)) {
                    return $this->htmlView->echoHTML($this->memberView->loggedinView());
                } else {
                    $this->publishView->fileAlreadyExistsMessage();
                    return $this->htmlView->echoHTML($this->publishView->publishView());
                }
            } else {
                $this->publishView->errorFileMessage();
                return $this->htmlView->echoHTML($this->publishView->publishView());
            }
        }

        if ($this->memberView->didUserPressMemeCategory()) {
            $this->publishModel->getFilesFromDB($this->memberView->didUserPressMemeCategory());
        }
        if ($this->memberView->didUserPressNatureCategory()) {
            $this->publishModel->getFilesFromDB($this->memberView->didUserPressNatureCategory());
        }
        if ($this->memberView->didUserPressFoodCategory()) {
            $this->publishModel->getFilesFromDB($this->memberView->didUserPressFoodCategory());
        }
        if ($this->memberView->didUserPressAlternativeCategory()) {
            $this->publishModel->getFilesFromDB($this->memberView->didUserPressAlternativeCategory());
        }

        if ($this->memberView->didUserPressDeleteFileButton()) {
            $chosenFile = $this->memberView->didUserPressDeleteFileButton();
            $this->publishModel->deleteFile($chosenFile);
        }

        if ($this->memberView->didUserComment()) {
            $filechoice = $this->memberView->didUserComment();
            $comment = $this->memberView->getComment();
            $this->publishModel->getComment($filechoice, $comment);
        }

        if ($this->publishView->didUserPressReturnButton()) {
            return $this->htmlView->echoHTML($this->memberView->loggedinView());
        }

        if ($this->loginModel->checkSession()) {
            return $this->htmlView->echoHTML($this->memberView->loggedinView());
        }

        if ($this->cookieStorage->checkCookie()) {
            $this->memberView->setSavedCookieMSG();
            $this->loginModel->setSession();
            return $this->htmlView->echoHTML($this->memberView->loggedinView());
        }

        if ($this->loginView->didUserPressLoginButton()) {
            $this->checkLogin();
            if ($this->loginModel->checkLogin($this->username, $this->password)) {
                if ($this->loginView->didUserPressLoginKeeperButton()) {
                    $this->loginModel->setSession();
                    $this->cookieStorage->createCookie();
                    $this->isSavedValidUser();
                }
                else {
                    $this->loginModel->setSession();
                    $this->isValidUser();
                }
            }
        }

        if (!$this->loginModel->checkSession()) {
            return $this->htmlView->echoHTML($this->loginView->show());
        }
    }

    public function isValidUser() {
        $this->memberView->setLoginMSG();
        return $this->htmlView->echoHTML($this->memberView->loggedinView());
    }

    public function isSavedValidUser() {
        $this->memberView->setSavedLoginMSG();
        return $this->htmlView->echoHTML($this->memberView->loggedinView());
    }

    public function errorMessageHandler() {
        $this->errorMSG = $this->loginModel->getErrorMessage();
        $this->loginView->setErrorMessage($this->errorMSG);
    }
}