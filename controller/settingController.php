<?php
include("../database/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image = $_FILES['profile-picture'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $currPassword =  $_POST['curr-password'];
    $password = $currPassword;
    $file_name = 'default.png';

    $errorMsg = array();
    if (empty($email) || empty($password) || empty($currPassword)) {
        array_push($errorMsg, "All fields must be filled");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errorMsg, "Email inputted must be a valid format email");
    }
    if (checkEmail($email)) {
        array_push($errorMsg, "Email in use");
    }
    if (!empty($_POST['password'])) {
        $password = $_POST['password'];
        if (strlen($password) < 8) {
            array_push($errorMsg, "Password length must be at least 8 characters");
        }
        if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            array_push($errorMsg, "Password must contain alphabet and number");
        }
    }
    if ($image["size"] == 0 && !is_uploaded_file($image['tmp_name'])) {
    } else {
        $target_directory = '../asset/image/profile-picture/';
        $file_name = $target_directory . time() . "_" . basename($image["name"]);
        $mime_type = mime_content_type($image["tmp_name"]);

        if (!in_array($mime_type, array('image/jpeg', 'image/png'))) {
            array_push($errorMsg, "Image must be .jpg / .png");
        } else {
            if (file_exists($file_name))
                array_push($errorMsg, "Image already exist!");
            if ($image["size"] > 1000000)
                array_push($errorMsg, "Image must be under 1MB");
            if (count($errorMsg) == 0) {
                move_uploaded_file($image['tmp_name'], $file_name);
                $file_name = time() . "_" . basename($image["name"]);
            }
        }
    }
    if (!checkPassword($_SESSION['id'], $currPassword)) {
        array_push($errorMsg, "Current Password invalid");
    }

    unset($_SESSION['error']);
    if (count($errorMsg) > 0) {
        $_SESSION['error'] = $errorMsg;
        header('Location: ../settings.php');
    } else {
        $detail = getUserDetail($_SESSION['id']);
        while ($data = $detail->fetch_assoc()) {
            $img = "../asset/image/profile-picture/" . $data['image_url'];
            if ($data['image_url'] != 'default.png') {
                unlink($img);
            }
        }
        updateSetting($_SESSION['id'], $username, $email, password_hash($password, PASSWORD_DEFAULT), $file_name);


        header('Location: ../profile.php');
    }

    // END HERE
}