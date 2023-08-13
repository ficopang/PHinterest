<?php
include("./database/db.php");
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
    <link rel="stylesheet" href="./style/css/pin.css">
    <link rel="shortcut icon" href="./asset/icon/favicon.png" type="image/x-icon" />
    <script src="./script/jquery-3.6.0.js"></script>
</head>

<body>
    <?php
    include './component/header.php';
    ?>
    <div class="app-content">
        <div class="pin-container">
            <?php
            $detail = getPinDetail($_GET['id']);
            $pin = $detail->fetch_assoc();

            ?>
            <div class="image-container">
                <?php
                if ($pin['type'] != "image") {
                ?>
                <video autoplay loop muted src=<?= "asset/video/" . $pin['url'] ?>></video>
                <?php } else { ?>
                <img src=<?= "asset/image/pin/" . $pin['url'] ?>>
                <?php } ?>
            </div>
            <div class="information-container">
                <form action="[URL]" method="post">
                    <div class="board-container">
                        <div class="select-board-container">
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
                <div class="title">
                    <!-- Show pin title -->
                    <?= $pin['title'] ?>
                </div>
                <div class="description">
                    <!-- Show pin description -->
                    <?= $pin['description'] ?>
                </div>
                <a href="profile.php?id=<?= $pin['added_by'] ?>" class="uploader-container">
                    <?php
                    $userDetail = getUserDetail($_SESSION['id']);
                    $user = $userDetail->fetch_assoc();
                    ?>

                    <div class="picture-wrapper">
                        <img src="<?= "asset/image/profile-picture/" . $user['image_url'] ?>">
                    </div>
                    <div class="uploader-name">
                        <!-- Show uploader username -->
                        <?= $user['username'] ?>
                    </div>
                </a>
            </div>
        </div>
        <div class="related-pin-container">
            <div class="title">
                More like this
            </div>
            <div class="images-container">
                <?php
                $pins = getAllUserPin($_SESSION['id']);
                while ($pin = $pins->fetch_assoc()) {
                    if ($pin['id'] == $_GET['id']) {
                        continue;
                    }
                ?>
                <div class="card-container">
                    <div class="card-image">
                        <div class="card-overlay">
                            <form action="[URL]" method="post">
                                <div class="overlay-wrapper">
                                    <div class="select-board-container">
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
                        <a href="pin.php?id=<?= $pin['id'] ?>">
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
    </div>
    <?php
    include './component/create-fab.php';
    ?>
</body>

</html>