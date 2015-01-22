<?php

require_once('./Model/loginModel.php');
require_once('./Model/publishModel.php');
require_once('./View/publishView.php');

class MemberView {

    private $loginModel;
    private $publishModel;
    private $activeUser;
    private $files;
    private $categoryID;
    private $loginMSG;

    public function __construct() {
        $this->loginModel = new LoginModel();
        $this->publishModel = new PublishModel();
    }

    public function didUserPressLogoutButton() {
        if (isset($_POST['logoutButton'])) {
            return true;
        }
    }

    public function didUserPressPublishButton() {
        if (isset($_POST['publishButton'])) {
            return true;
        }
    }

    public function didUserPressMemeCategory() {
        if (isset($_POST['memeButton'])) {
            return $_POST['memeButton'];
        }
    }

    public function didUserPressNatureCategory() {
        if (isset($_POST['natureButton'])) {
            return $_POST['natureButton'];
        }
    }

    public function didUserPressFoodCategory() {
        if (isset($_POST['foodButton'])) {
            return $_POST['foodButton'];
        }
    }

    public function didUserPressAlternativeCategory() {
        if (isset($_POST['alternativeButton'])) {
            return $_POST['alternativeButton'];
        }
    }

    public function didUserPressDeleteFileButton() {
        if (isset($_POST['deleteFileButton'])) {
            return $_POST['deleteFileButton'];
        }
    }

    public function didUserPressDeleteCommentButton() {
        if (isset($_POST['deleteComment'])) {
            return $_POST['deleteComment'];
        }
    }

    public function didUserComment() {
        if (isset($_POST['submitComment'])) {
            return $_POST['submitComment'];
        }
    }

    public function getComment() {
        if (isset($_POST['submitComment'])) {
            return $_POST['comment'];
        }
    }

    public function setLoginMSG() {
        $this->loginMSG = "Inloggningen lyckades.";
    }

    public function setSavedLoginMSG() {
        $this->loginMSG = "Inloggningen lyckades och vi kommer ihåg dig till nästa gång.";
    }

    public function setSavedCookieMSG() {
        $this->loginMSG = "Inloggningen lyckades via cookies.";
    }

    //Returns a view depending on which category is chosen, if none is chosen returns all objects.
    public function loggedinView() {
        $this->activeUser = $_SESSION['login'];

        $ret = "<div id = 'memberView' >
                    <div id ='headerBox'>
                    <p id = 'loginMessage'>$this->loginMSG</p>
                        <form method='POST' action='?logout'>
                        <input id='logoutButton' type='submit' name='logoutButton' value='Logga ut'>
                        </form>
                        <h1 id = 'mainTitleLoggedIn'>Bildblogg</h1>
                        <form method='POST' action='?upload'>
                        <div id = 'uploadBox'>
                            <input id='uploadButton' type='submit' name='publishButton' value='Skapa nytt inlägg'>
                        </div>
                        </form>
                    </div>
                        <div class = 'form-inline'>
                            <form method='POST' action='?memes'>
                            <div class = 'form-group'>
                            <input id='memeButton' type='submit' name='memeButton' value='Memes'>
                            </div>
                            </form>
                            <form method='POST' action='?natur'>
                            <div class = 'form-group'>
                            <input id='natureButton' type='submit' name='natureButton' value='Natur'>
                            </div>
                            </form>
                            <form method='POST' action='?mat'>
                            <div class = 'form-group'>
                            <input id='foodButton' type='submit' name='foodButton' value='Mat'>
                            </div>
                            </form>
                            <form method='POST' action='?ovrigt'>
                            <div class = 'form-group'>
                            <input id='alternativeButton' type='submit' name='alternativeButton' value='Övrigt'>
                            </div>
                            </form>
                        </div>
                        ";
        if ($this->didUserPressMemeCategory()) {
            $this->categoryID = $_POST['memeButton'];
            //this->files contains all the objects from the db that have the chosen categorytype.
            $this->files = $this->publishModel->getFilesFromDB($this->categoryID);

            //Loop through the multidimensional file array.
            foreach ($this->files as $file) {
                $ret .= "<div id = 'imgArticle'>
                <form method='POST' action='?main'>
                <input id='deleteFileButton' type='hidden' name='deleteFileButton' value='$file[0]'>
                <input id='deleteFileButton' type='submit' value='X'>
                </form>
                <img id = 'image' src=$file[0] alt='image' width='470'>";
                $ret .= "<p>$file[1]</p>
                <p id='commentText'>Ange kommentar:</p>
                <form method='POST' action=?main>
                <textarea id='commentBox' rows='4' cols='71' name='comment'></textarea>
                <input id='submitComment' type='hidden' name='submitComment' value='$file[0]'>
                <input id='submitComment' type='submit' value='Skicka'>
                </form>";
                //Loop through all the comments connected to the specific file.
                foreach ($this->publishModel->getCommentsFromDB($file[2]) as $comment) {
                    $ret .= "
                        <div id = comments>
                        <form method='POST' action=?main>
                        <input id='deleteComment' type='hidden' name='deleteComment' value='$comment[1]'>
                        <input id='deleteComment' type='submit' value='X'>
                        </form>
                        <p>$comment[0]</p>
                        </div>
                        ";
                }
                $ret .= "
                </div>";
            }
            $ret .=" </div>";

            return $ret;
        }
        if ($this->didUserPressNatureCategory()) {
            $this->categoryID = $_POST['natureButton'];
            $this->files = $this->publishModel->getFilesFromDB($this->categoryID);
            foreach ($this->files as $file) {

                $ret .= "<div id = 'imgArticle'>
                <form method='POST' action='?main'>
                <input id='deleteFileButton' type='hidden' name='deleteFileButton' value='$file[0]'>
                <input id='deleteFileButton' type='submit' value='X'>
                </form>
                <img id = 'image' src=$file[0] alt='image' width='470'>";
                $ret .= "<p>$file[1]</p>
                <p id='commentText'>Ange kommentar:</p>
                <form method='POST' action=?main>
                <textarea id='commentBox' rows='4' cols='71' name='comment'></textarea>
                <input id='submitComment' type='hidden' name='submitComment' value='$file[0]'>
                <input id='submitComment' type='submit' value='Skicka'>
                </form>";
                foreach ($this->publishModel->getCommentsFromDB($file[2]) as $comment) {
                    $ret .= "
                        <div id = comments>
                        <form method='POST' action=?main>
                        <input id='deleteComment' type='hidden' name='deleteComment' value='$comment[1]'>
                        <input id='deleteComment' type='submit' value='X'>
                        </form>
                        <p>$comment[0]</p>
                        </div>
                        ";
                }
                $ret .= "
                </div>";
            }
            $ret .=" </div>";

            return $ret;
        }
        if ($this->didUserPressFoodCategory()) {
            $this->categoryID = $_POST['foodButton'];
            $this->files = $this->publishModel->getFilesFromDB($this->categoryID);

            foreach ($this->files as $file) {
                $ret .= "<div id = 'imgArticle'>
                <form method='POST' action='?main'>
                <input id='deleteFileButton' type='hidden' name='deleteFileButton' value='$file[0]'>
                <input id='deleteFileButton' type='submit' value='X'>
                </form>
                <img id = 'image' src=$file[0] alt='image' width='470'>";
                $ret .= "<p>$file[1]</p>
                <p id='commentText'>Ange kommentar:</p>
                <form method='POST' action=?main>
                <textarea id='commentBox' rows='4' cols='71' name='comment'></textarea>
                <input id='submitComment' type='hidden' name='submitComment' value='$file[0]'>
                <input id='submitComment' type='submit' value='Skicka'>
                </form>";
                foreach ($this->publishModel->getCommentsFromDB($file[2]) as $comment) {
                    $ret .= "
                        <div id = comments>
                        <form method='POST' action=?main>
                        <input id='deleteComment' type='hidden' name='deleteComment' value='$comment[1]'>
                        <input id='deleteComment' type='submit' value='X'>
                        </form>
                        <p>$comment[0]</p>
                        </div>
                        ";
                }
                $ret .= "
                </div>";
            }
            $ret .=" </div>";

            return $ret;
        }
        if ($this->didUserPressAlternativeCategory()) {
            $this->categoryID = $_POST['alternativeButton'];
            $this->files = $this->publishModel->getFilesFromDB($this->categoryID);

            foreach ($this->files as $file) {
                $ret .= "<div id = 'imgArticle'>
                <form method='POST' action='?main'>
                <input id='deleteFileButton' type='hidden' name='deleteFileButton' value='$file[0]'>
                <input id='deleteFileButton' type='submit' value='X'>
                </form>
                <img id = 'image' src=$file[0] alt='image' width='470'>";
                $ret .= "<p>$file[1]</p>
                <p id='commentText'>Ange kommentar:</p>
                <form method='POST' action=?main>
                <textarea id='commentBox' rows='4' cols='71' name='comment'></textarea>
                <input id='submitComment' type='hidden' name='submitComment' value='$file[0]'>
                <input id='submitComment' type='submit' value='Skicka'>
                </form>";
                foreach ($this->publishModel->getCommentsFromDB($file[2]) as $comment) {
                    $ret .= "
                        <div id = comments>
                        <form method='POST' action=?main>
                        <input id='deleteComment' type='hidden' name='deleteComment' value='$comment[1]'>
                        <input id='deleteComment' type='submit' value='X'>
                        </form>
                        <p>$comment[0]</p>
                        </div>
                        ";
                }
                $ret .= "
                </div>";
            }
            $ret .=" </div>";

            return $ret;
        } else {
            $this->files = $this->publishModel->getFilesFromDB($this->categoryID);
            foreach ($this->files as $file) {
                $ret .= "<div id = 'imgArticle'>
                <form method='POST' action='?main'>
                <input id='deleteFileButton' type='hidden' name='deleteFileButton' value='$file[0]'>
                <input id='deleteFileButton' type='submit' value='X'>
                </form>
                <img id = 'image' src=$file[0] alt='image' width='470'>";
                $ret .= "<p>$file[1]</p>
                <p id='commentText'>Ange kommentar:</p>
                <form method='POST' action=?main>
                <textarea id='commentBox' rows='4' cols='71' name='comment'></textarea>
                <input id='submitComment' type='hidden' name='submitComment' value='$file[0]'>
                <input id='submitComment' type='submit' value='Skicka'>
                </form>";
                foreach ($this->publishModel->getCommentsFromDB($file[2]) as $comment) {
                    $ret .= "
                        <div id = comments>
                        <form method='POST' action=?main>
                        <input id='deleteComment' type='hidden' name='deleteComment' value='$comment[1]'>
                        <input id='deleteComment' type='submit' value='X'>
                        </form>
                        <p>$comment[0]</p>
                        </div>
                        ";
                }
                $ret .= "
                </div>";
            }
            $ret .=" </div>";

            return $ret;
        }

    }

}

