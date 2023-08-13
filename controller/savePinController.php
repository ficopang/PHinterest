<?php
include("../database/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pin_id = $_POST['pin_id'];
    $board = $_POST['board'];

    $errorMsg = array();

    unset($_SESSION['error']);
    if (count($errorMsg) > 0) {
        $_SESSION['error'] = $errorMsg;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        savePin($pin_id, $board);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    // END HERE
}