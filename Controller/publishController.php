<?php

require_once('./View/HTMLview.php');
require_once('./View/memberView.php');
require_once('./View/publishView.php');
require_once('./Model/publishModel.php');

class PublishController {

    private $htmlView;
    private $memberView;
    private $publishView;
    private $publishModel;

    public function __construct() {
        $this->htmlView = new HTMLView();
        $this->memberView = new MemberView();
        $this->publishView = new PublishView();
        $this->publishModel = new PublishModel();
    }

    public function publisher() {
        if ($this->memberView->didUserPressDeleteCommentButton()) {
            $chosenComment = $this->memberView->didUserPressDeleteCommentButton();
            $this->publishModel->deleteComment($chosenComment);
        }

        //Checks if user want to upload a file then sends the input information to the publishModel for control and uploading.
        if($this->publishView->uploadFile()) {
            $this->publishView->setFileName();
            $fileName = $this->publishView->getName();
            $filePath = $this->publishView->getFileName();
            $imageDescription = $this->publishView->getFileDescription();
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

        if ($this->memberView->didUserChoseCategory()) {
            $this->publishModel->getFilesFromDB($this->memberView->didUserChoseCategory());
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
    }
}