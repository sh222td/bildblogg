<?php

require_once('./Model/loginModel.php');
require_once('./Model/publishModel.php');
require_once('./Controller/publishController.php');
require_once('./View/loginView.php');
require_once('./View/HTMLview.php');
require_once('./View/memberView.php');
require_once('./View/publishView.php');
require_once('./cookieStorage.php');

class LoginController {

    private $username;
    private $password;
    private $htmlView;
    private $loginView;
    private $memberView;
    private $publishController;
    private $cookieStorage;
    private $loginModel;
    private $errorMSG;
    private $publishView;

    public function __construct() {
        $this->htmlView = new HTMLView();
        $this->loginView = new LoginView();
        $this->memberView = new MemberView();
        $this->publishController = new PublishController();
        $this->loginModel = new LoginModel();
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
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        if ($this->memberView->didUserPressLogoutButton()) {
            $this->loginModel->killSession();
            $this->cookieStorage->unsetCookie();
            return $this->htmlView->echoHTML($this->loginView->show());
        }
        /*
         * if-sentence that checks if there is a session and which url is active and returns the suiting view
         * the ?post action can return 2 different views from the publishController if the post was successful or not
        */
        if ($this->loginModel->checkSession()) {
          if (strpos($url,'?upload') !== false) {
              $this->publishController->publisher();
              return $this->htmlView->echoHTML($this->publishView->publishView());
          }
          else if (strpos($url,'?main') !== false) {
              $this->publishController->publisher();
              return $this->htmlView->echoHTML($this->memberView->loggedinView());
          }
          else if (strpos($url,'?post') !==false) {
              return $this->publishController->publisher();
          } else {
              $this->publishController->publisher();
              return $this->htmlView->echoHTML($this->memberView->loggedinView());
          }
        }

        if ($this->cookieStorage->checkCookie()) {
            $this->memberView->setSavedCookieMSG();
            $this->loginModel->setSession();
            $this->publishController->publisher();
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
        $this->publishController->publisher();
        return $this->htmlView->echoHTML($this->memberView->loggedinView());
    }

    public function isSavedValidUser() {
        $this->memberView->setSavedLoginMSG();
        $this->publishController->publisher();
        return $this->htmlView->echoHTML($this->memberView->loggedinView());
    }

    public function errorMessageHandler() {
        $this->errorMSG = $this->loginModel->getErrorMessage();
        $this->loginView->setErrorMessage($this->errorMSG);
    }
}