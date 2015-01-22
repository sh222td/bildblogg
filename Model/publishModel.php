<?php
require_once('./properties.php');

class PublishModel
{

    private $name;
    private $path;
    private $properties;
    private $fileErrorMSG;

    public function __construct() {
        $this->properties = new Properties();
    }

    //Get the file and upload it to the FTP server and database.
    public function getFiles($name, $path, $imageDescription, $radioButton, $chmodValue) {
        $this->name = $name;
        $this->path = $path;

        //Settings for the connection.
        $ftp_server = "ftp.sandrahansson.net";
        $ftp_user_name = "135026-master";
        $ftp_user_pass = "morotskaka";
        $remote_file = '/sandrahansson.net/public_html/bildblogg/images/'.$name;
        $url = 'http://sandrahansson.net/bildblogg/images/'.$name;

        //Connect to the server.
        $conn_id = ftp_connect($ftp_server);
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

        if ($this->checkFile() !== true) {
            //Connect to database.
            $db = $this->properties->dbReader();

            //Add the file to the FTP server
            if (ftp_put($conn_id, $remote_file, $this->path, FTP_BINARY)) {
                //Add rights to the file via FTP
                if (ftp_chmod($conn_id, $chmodValue, $remote_file) == true) {

                }
                ftp_close($conn_id);

                //Add the file to the database
                $description = strip_tags($imageDescription);
                $sqlQuery = "INSERT INTO file VALUE ('', '$name', '$url', '$description', '$radioButton')";
                $result = mysqli_query($db, $sqlQuery);
                return true;
            }
        } else {
            return false;
        }


    }

    //Checks if the uploaded file already exists in the db.
    public function checkFile() {
        $db = $this->properties->dbReader();
        $getFiles = mysqli_query($db, "SELECT filename FROM file");

        while ($row = mysqli_fetch_array($getFiles)) {
            $filename = $row['filename'];

            if ($this->name == $filename) {
                return true;
            }
        }
    }

    public function getfileErrorMessage() {
        return $this->fileErrorMSG;
    }

    //Function that fetch the objects from the database depending on what category has been declared.
    public function getFilesFromDB($categoryID) {
        $db = $this->properties->dbReader();

        $files = [];

        if ($categoryID == "Memes") {
            //Asking a SQL question to the db and loop through the result.
            $getMemeFiles = mysqli_query($db, "SELECT * FROM file WHERE category = 1");
            while ($row = mysqli_fetch_assoc($getMemeFiles)) {
                $url = $row['filepath'];
                $description = $row['description'];
                $fileID = $row['fileID'];

                $files[] = array($url, $description, $fileID);
            }
            //Return a reversed array.
            $reversedArr = array_reverse($files);
            return $reversedArr;
        }
        if ($categoryID == "Natur") {
            $getNatureFiles = mysqli_query($db, "SELECT * FROM file WHERE category = 2");
            while ($row = mysqli_fetch_assoc($getNatureFiles)) {
                $url = $row['filepath'];
                $description = $row['description'];
                $fileID = $row['fileID'];

                $files[] = array($url, $description, $fileID);
            }
            $reversedArr = array_reverse($files);
            return $reversedArr;
        }
        if ($categoryID == "Mat") {
            $getFoodFiles = mysqli_query($db, "SELECT * FROM file WHERE category = 3");
            while ($row = mysqli_fetch_assoc($getFoodFiles)) {
                $url = $row['filepath'];
                $description = $row['description'];
                $fileID = $row['fileID'];

                $files[] = array($url, $description, $fileID);
            }
            $reversedArr = array_reverse($files);
            return $reversedArr;
        }
        if ($categoryID == "Övrigt") {
            $getAlternativeFiles = mysqli_query($db, "SELECT * FROM file WHERE category = 4");
            while ($row = mysqli_fetch_assoc($getAlternativeFiles)) {
                $url = $row['filepath'];
                $description = $row['description'];
                $fileID = $row['fileID'];

                $files[] = array($url, $description, $fileID);
            }
            $reversedArr = array_reverse($files);
            return $reversedArr;
        } else {
            $result = mysqli_query($db, "SELECT * FROM file");
            while ($row = mysqli_fetch_assoc($result)) {
                $url = $row['filepath'];
                $description = $row['description'];
                $fileID = $row['fileID'];

                $files[] = array($url, $description, $fileID);
            }
            $reversedArr = array_reverse($files);
            return $reversedArr;
        }
    }

    public function deleteFile($chosenfile) {
        //Remove from FTP server.
        $db = $this->properties->dbReader();
        $getFileName = mysqli_query($db, "SELECT filename FROM file WHERE filepath='$chosenfile'");

        $row = mysqli_fetch_assoc($getFileName);
        $filename = $row['filename'];

        $ftp_server = "ftp.sandrahansson.net";
        $ftp_user_name = "135026-master";
        $ftp_user_pass = "morotskaka";
        $file = '/sandrahansson.net/public_html/bildblogg/images/' . $filename;

        $conn_id = ftp_connect($ftp_server);
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

        ftp_delete($conn_id, $file);

        ftp_close($conn_id);

        //Remove from database.
        $result = mysqli_query($db, "DELETE FROM file WHERE filepath='$chosenfile'");
    }

    //Function that fetch the comment and adds it to the database.
    public function getComment($filechoice, $comment) {
        $db = $this->properties->dbReader();

        $getFileID = mysqli_query($db, "SELECT fileID FROM file WHERE filepath='$filechoice'");
        $row = mysqli_fetch_assoc($getFileID);
        $fileID = $row['fileID'];
        $commentInput = strip_tags($comment);

        $addComment = mysqli_query($db, "INSERT INTO comments (fileID, comment) VALUES ('$fileID', '$commentInput')");
    }

    public function getCommentsFromDB($fileID) {
        //Get comment from specific file.
        $db = $this->properties->dbReader();

        $result = mysqli_query($db, "SELECT * FROM comments WHERE fileID = '$fileID'");
        $comments = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $comment = $row['comment'];
            $commentID = $row['commentID'];
            $comments[] = array($comment, $commentID);
        }
        $reversedArr = array_reverse($comments);
        return $reversedArr;
    }

    //Function that removes comment from database.
    public function deleteComment($chosenfile) {
        $db = $this->properties->dbReader();
        $getCommentID = mysqli_query($db, "SELECT commentID FROM comments WHERE comment = '$chosenfile'");
        $row = mysqli_fetch_assoc($getCommentID);

        $result = mysqli_query($db, "DELETE FROM `comments` WHERE `commentID` = '$chosenfile'");
    }
}