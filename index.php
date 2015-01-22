<?php
session_start();
require_once('Controller/loginController.php');

$loginController = new loginController();
$loginController->startController();