<?php
include("../database/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];

    $errorMsg = array();
    if (empty($name)) {
        array_push($errorMsg, "Name cant be empty");
    }

    unset($_SESSION['error']);
    if (count($errorMsg) > 0) {
        $_SESSION['error'] = $errorMsg;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        createBoard($_SESSION['id'], $name);
        header('Location: ../profile.php');
    }

    // END HERE
}