<?php

$config = require_once("config.php");

$conn = mysqli_connect($config["server"], $config["username"], $config["password"], $config["database"]);

if ($conn->connect_error) {
    die("Connection to DB failed!");
}

session_start();

// user-defined function
function checkEmail($email)
{
    global $conn;

    $query = "SELECT * FROM users WHERE email='" . $email . "'";
    $res = $conn->query($query);

    if ($res->num_rows == 1) {
        return true;
    }
    return false;
}

function register($email, $password, $age)
{
    global $conn;

    $query = "INSERT INTO users VALUES (NULL, '" . strtok($email, '@') . "', '" . $email . "', '" . $password . "', '" . $age . "', 'default.png')";

    if ($conn->query($query)) {
        return true;
    }

    return false;
}

function auth($email, $password)
{
    global $conn;

    $query = "SELECT * FROM users WHERE email='" . $email . "'";
    $res = $conn->query($query);

    if ($res->num_rows == 1) {
        $data = $res->fetch_assoc();
        if (password_verify($password, $data['password'])) {
            return $data;
        }
        return false;
    }
    return false;
}

function checkPassword($id, $password)
{
    global $conn;

    $query = "SELECT * FROM users WHERE id='" . $id . "'";
    $res = $conn->query($query);

    if ($res->num_rows == 1) {
        $data = $res->fetch_assoc();
        if (password_verify($password, $data['password'])) {
            return $data;
        }
        return false;
    }
    return false;
}

function getAllUserPin($id)
{
    global $conn;

    $query = "SELECT * FROM pins WHERE added_by='" . $id . "' ORDER BY rand()";
    return $conn->query($query);
}

function getAllUserBoard($id)
{
    global $conn;

    $query = "SELECT * FROM user_board WHERE owner='" . $id . "'";
    return $conn->query($query);
}

function getUserBoardAndPin($id)
{
    global $conn;

    $query = "SELECT user_board.id as id, count(*) as total_pins, user_board.name as name, GROUP_CONCAT(url ORDER BY pins.id DESC SEPARATOR '|') as preview FROM pins RIGHT JOIN user_board ON board_id=user_board.id WHERE owner =" . $id . " GROUP BY user_board.name";
    return $conn->query($query);
}

function getUserBoardAndPinByBoardId($id)
{
    global $conn;

    $query = "SELECT count(*) as total_pins, user_board.name as name, GROUP_CONCAT(pins.id ORDER BY pins.id DESC SEPARATOR '|') as pins_id, GROUP_CONCAT(title ORDER BY pins.id DESC SEPARATOR '|') as pins_title, GROUP_CONCAT(url ORDER BY pins.id DESC SEPARATOR '|') as pins_url, GROUP_CONCAT(type ORDER BY pins.id DESC SEPARATOR '|') as pins_type, GROUP_CONCAT(board_id ORDER BY pins.id DESC SEPARATOR '|') as pins_board_id, GROUP_CONCAT(added_by ORDER BY pins.id DESC SEPARATOR '|') as pins_added_by FROM pins INNER JOIN user_board ON board_id=user_board.id WHERE user_board.id ='" . $id . "' GROUP BY user_board.name";
    return $conn->query($query);
}

function getUserDetail($id)
{
    global $conn;

    $query = "SELECT * FROM users WHERE id='" . $id . "'";
    return $conn->query($query);
}

function getPinDetail($id)
{
    global $conn;

    $query = "SELECT * FROM pins WHERE id='" . $id . "'";
    return $conn->query($query);
}

function searchPin($title)
{
    global $conn;

    $query = "SELECT * FROM pins WHERE title LIKE '%" . $title . "%' ORDER BY rand()";
    return $conn->query($query);
}

function updateSetting($id, $username, $email, $password, $image_url)
{
    global $conn;

    $query = "UPDATE users SET username='" . $username . "', email='" . $email . "',password='" . $password . "', image_url='" . $image_url . "' WHERE id='" . $id . "'";
    if ($conn->query($query)) {
        return true;
    }

    return false;
}

function updateBoard($id, $name)
{
    global $conn;

    $query = "UPDATE user_board SET `name`='" . $name . "' WHERE id='" . $id . "'";
    if ($conn->query($query)) {
        return true;
    }

    return false;
}

function deleteBoard($id)
{
    global $conn;

    $query = "DELETE FROM user_board WHERE id='" . $id . "'";
    if ($conn->query($query)) {
        return true;
    }

    return false;
}

function createBoard($id, $name)
{
    global $conn;

    $query = "INSERT INTO user_board VALUES (NULL, '" . $name . "', '" . $id . "')";
    if ($conn->query($query)) {
        return true;
    }

    return false;
}

function createPin($title, $description, $type, $url, $added_by, $board_id)
{
    global $conn;

    $query = "INSERT INTO pins VALUES (NULL, '" . $title . "', '" . $description . "', '" . $type . "', '" . $url . "', '" . $added_by . "', '" . $board_id . "')";
    if ($conn->query($query)) {
        return true;
    }

    return false;
}

function savePin($pin_id, $board_id)
{
    global $conn;

    $query = "UPDATE pins SET `board_id`='" . $board_id . "' WHERE id='" . $pin_id . "'";
    if ($conn->query($query)) {
        return true;
    }

    return false;
}