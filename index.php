<?php
session_start();
require_once('Controller/loginController.php');

$loginController = new LoginController();
$loginController->startController();