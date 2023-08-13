<?php
include("../database/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errorMsg = array();

    unset($_SESSION['error']);
    if (count($errorMsg) > 0) {
        $_SESSION['error'] = $errorMsg;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        deleteBoard($_POST['id']);
        header('Location: ../profile.php');
    }
    // END HERE
}