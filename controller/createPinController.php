<?php
include("../database/db.php");

$file_name = "";
$type = 'image';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image = $_FILES['pin'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $board_id = $_POST['board'];

    $file_name = 'default.png';
    $errorMsg = array();

    if (empty($title)) {
        array_push($errorMsg, "All fields must be filled");
    }
    if ($image["size"] == 0 && !is_uploaded_file($image['tmp_name'])) {
        array_push($errorMsg, "File must be chosen");
    } else {
        $target_directory = '../asset/image/pin/';
        if (strcmp(pathinfo($image["name"], PATHINFO_EXTENSION), 'mp4') == 0) {
            $target_directory = '../asset/video/';
            $type = 'video';
        }
        $file_name = $target_directory . time() . "_" . basename($image["name"]);
        $mime_type = mime_content_type($image["tmp_name"]);

        if (!in_array($mime_type, array('image/jpeg', 'image/png', 'video/mp4'))) {
            array_push($errorMsg, "file must be .jpg / .png / .mp4");
        } else {
            if (file_exists($file_name))
                array_push($errorMsg, "file already exist!");
            if ($image["size"] > 20000000)
                array_push($errorMsg, "file must be under 20MB");
            if (count($errorMsg) == 0) {
                move_uploaded_file($image['tmp_name'], $file_name);
                $file_name = time() . "_" . basename($image["name"]);
            }
        }
    }

    unset($_SESSION['error']);
    if (count($errorMsg) > 0) {
        $_SESSION['error'] = $errorMsg;
        header('Location: ../postContent.php');
    } else {
        if (createPin(
            $title,
            $description,
            $type,
            $file_name,
            $_SESSION['id'],
            $board_id
        )) {
            header('Location: ../profile.php');
        }
    }

    // END HERE
}