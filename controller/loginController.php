<?php
include("../database/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = $_POST['remember-me'];

    $errorMsg = array();
    if (empty($email) || empty($password)) {
        array_push($errorMsg, "All fields must be filled");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errorMsg, "Email inputted must be a valid format email");
    }
    if (strlen($password) < 8) {
        array_push($errorMsg, "Password length must be at least 8 characters");
    }
    if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        array_push($errorMsg, "Password must contain alphabet and number");
    }

    unset($_SESSION['error']);
    if (count($errorMsg) > 0) {
        $_SESSION['error'] = $errorMsg;
        header('Location: ../login.php');
    } else if ($res = auth($_POST['email'], $_POST['password'])) {
        if (isset($_POST['remember-me'])) {
            setcookie("id", $res['id'], time() + 60 * 60 * 24 * 7, "/", NULL, 0);
        }
        $_SESSION['id'] = $res['id'];
        header('Location: ../index.php');
    } else {
        array_push($errorMsg, "Wrong credentials");
        $_SESSION['error'] = $errorMsg;
        header('Location: ../login.php');
    }
}