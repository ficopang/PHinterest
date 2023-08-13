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
    <link rel="stylesheet" href="./style/css/board.css">
    <link rel="shortcut icon" href="./asset/icon/favicon.png" type="image/x-icon" />
    <script src="./script/jquery-3.6.0.js"></script>
</head>

<body>
    <?php
    include './component/header.php';
    ?>
    <div class="app-content">
        <?php
        if (isset($_GET['id'])) {
            $boards = getUserBoardAndPinByBoardId($_GET['id']);
            while ($board = $boards->fetch_assoc()) {
                $pins_id = explode("|", $board['pins_id']);
                $pins_title = explode("|", $board['pins_title']);
                $pins_url = explode("|", $board['pins_url']);
                $pins_type = explode("|", $board['pins_type']);
                $pins_board_id = explode("|", $board['pins_board_id']);
                $pins_added_by = explode("|", $board['pins_added_by']);
        ?>
        <div class="board-information-container">
            <form action="./controller/updateBoardController.php" method="post" id="update-form">
                <div class="name-wrapper">
                    <input type="text" name="name" value="<?= $board['name'] ?>">
                    <input type="text" style="display:none" name="id" value="<?= $_GET['id'] ?>">
                </div>
            </form>
            <div class="form-error">
                <!-- Show error message -->
                <?php
                        if (isset($_SESSION['error'])) {
                            echo $_SESSION['error'][0];
                            unset($_SESSION['error']);
                        }
                        ?>
            </div>
            <form style="display: none;" action="./controller/deleteBoardController.php" method="post" id="delete-form">
                <input type="text" style="display:none" name="id" value="<?= $_GET['id'] ?>">
            </form>

            <div class="btn-container">
                <div class="btn-wrapper" onclick="$('#update-form').submit()">
                    <div class="btn-icon">
                        <svg width="30px" height="30px" viewBox="0 0 576 512">
                            <path fill="currentColor"
                                d="M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zm162-22.9l-48.8-48.8c-15.2-15.2-39.9-15.2-55.2 0l-35.4 35.4c-3.8 3.8-3.8 10 0 13.8l90.2 90.2c3.8 3.8 10 3.8 13.8 0l35.4-35.4c15.2-15.3 15.2-40 0-55.2zM384 346.2V448H64V128h229.8c3.2 0 6.2-1.3 8.5-3.5l40-40c7.6-7.6 2.2-20.5-8.5-20.5H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V306.2c0-10.7-12.9-16-20.5-8.5l-40 40c-2.2 2.3-3.5 5.3-3.5 8.5z">
                            </path>
                        </svg>
                    </div>
                    <div class="btn-text">
                        Update
                    </div>
                </div>
                <div class="btn-wrapper" onclick="$('#delete-form').submit()">
                    <div class="btn-icon">
                        <svg width="30px" height="30px" viewBox="0 0 448 512">
                            <path fill="currentColor"
                                d="M32 464a48 48 0 0 0 48 48h288a48 48 0 0 0 48-48V128H32zm272-256a16 16 0 0 1 32 0v224a16 16 0 0 1-32 0zm-96 0a16 16 0 0 1 32 0v224a16 16 0 0 1-32 0zm-96 0a16 16 0 0 1 32 0v224a16 16 0 0 1-32 0zM432 32H312l-9.4-18.7A24 24 0 0 0 281.1 0H166.8a23.72 23.72 0 0 0-21.4 13.3L136 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16z">
                            </path>
                        </svg>
                    </div>
                    <div class="btn-text">
                        Delete
                    </div>
                </div>
            </div>
        </div>
        <div class="board-content-container">
            <div class="heading">
                <!-- Show total pins -->
                <?= $board['total_pins'] ?> Pins
            </div>
        </div>
        <div class="images-container">
            <?php
                    foreach ($pins_title as $index => $pin_title) {
                    ?>
            <div class="card-container">
                <div class="card-image">
                    <div class="card-overlay">
                        <form action="./controller/savePinController.php" method="post">
                            <div class="overlay-wrapper">
                                <div class="select-board-container">
                                    <input type="text" name="pin_id" style="display: none;"
                                        value="<?= $pins_id[$index] ?>">
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
                    <a href="board.php?id=<?= $pins_board_id[$index] ?>">
                        <?php
                                    if ($pins_type[$index] != "image") {
                                    ?>
                        <video autoplay loop muted src=<?= "asset/video/" . $pins_url[$index] ?>></video>
                        <?php } else { ?>
                        <img src=<?= "asset/image/pin/" . $pins_url[$index] ?>>
                        <?php } ?>
                    </a>
                </div>
                <div class="card-title">
                    <div class="title-text">
                        <!-- Show pin title -->
                        <?= $pins_title[$index] ?>
                    </div>
                    <?php
                                if ($pins_added_by[$index] == $_SESSION['id']) {
                                ?>
                    <form action="[URL]" method="post">
                        <button class="delete-icon" onclick="$('#delete-pin-form').submit()">
                            <svg width="20" height="20" viewBox="0 0 448 512">
                                <path fill="currentColor"
                                    d="M432 32H312l-9.4-18.7A24 24 0 0 0 281.1 0H166.8a23.72 23.72 0 0 0-21.4 13.3L136 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16zM53.2 467a48 48 0 0 0 47.9 45h245.8a48 48 0 0 0 47.9-45L416 128H32z">
                                </path>
                            </svg>
                        </button>
                    </form>
                    <?php
                                }
                                ?>
                </div>
            </div>
            <?php
                    }
                    ?>
        </div>
        <?php
            }
        }
        ?>
    </div>
    <?php
    include './component/create-fab.php';
    ?>
</body>

</html>