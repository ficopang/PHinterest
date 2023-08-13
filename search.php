<?php
include("./database/db.php");
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHinterest</title>
    <link rel="stylesheet" href="./style/css/base.css">
    <link rel="stylesheet" href="./style/css/fab.css">
    <link rel="stylesheet" href="./style/css/header.css">
    <link rel="stylesheet" href="./style/css/index.css">
    <link rel="shortcut icon" href="./asset/icon/favicon.png" type="image/x-icon" />
    <script src="./script/jquery-3.6.0.js"></script>
</head>

<body>
    <?php
    include './component/header.php';
    ?>
    <div class="app-content">
        <div class="images-container">
            <?php
            $pins = searchPin($_GET['title']);
            while ($pin = $pins->fetch_assoc()) { ?>
            <div class="card-container">
                <div class="card-image">
                    <div class="card-overlay">
                        <form action="./controller/savePinController.php" method="post">
                            <div class="overlay-wrapper">
                                <div class="select-board-container">
                                    <input type="text" name="pin_id" style="display: none;" value="<?= $pin['id'] ?>">
                                    <select name="board">
                                        <option value="">Select board</option>
                                        <!-- Show user's board -->
                                        <?php

                                            $boards = getAllUserBoard($_SESSION['id']);
                                            while ($board = $boards->fetch_assoc()) { ?>
                                        <option value="<?= $board['id'] ?>"><?= $board['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <button type="submit" class="save-pin-btn">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                    <a href=[URL]>
                        <?php
                            if ($pin['type'] != "image") {
                            ?>
                        <video autoplay loop muted src=<?= "asset/video/" . $pin['url'] ?>></video>
                        <?php } else { ?>
                        <img src=<?= "asset/image/pin/" . $pin['url'] ?>>
                        <?php } ?>
                    </a>
                </div>
                <div class="card-title">
                    <!-- Show pin title -->
                    <?= $pin['title'] ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php
    include './component/create-fab.php';
    ?>
</body>

</html>