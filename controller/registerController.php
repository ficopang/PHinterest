<?php
include("../database/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $age = $_POST['age'];

    $errorMsg = array();
    if (empty($email) || empty($password) || empty($age)) {
        array_push($errorMsg, "All fields must be filled");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errorMsg, "Email inputted must be a valid format email");
    }

    if (checkEmail($email)) {
        array_push($errorMsg, "Email in use");
    }
    if (strlen($password) < 8) {
        array_push($errorMsg, "Password length must be at least 8 characters");
    }
    if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        array_push($errorMsg, "Password must contain alphabet and number");
    }
    if (!is_numeric($age) && $age < 1) {
        array_push($errorMsg, " Age must be numeric and at least 1");
    }

    unset($_SESSION['error']);
    if (count($errorMsg) > 0) {
        $_SESSION['error'] = $errorMsg;
        header('Location: ../register.php');
    } else {
        register($email, password_hash($password, PASSWORD_DEFAULT), $age);
        header('Location: ../login.php');
    }

    // END HERE
}