<?php
include("./database/db.php");
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
}

if (isset($_GET['id'])) {
    $detail = getUserDetail($_GET['id']);
    while ($data = $detail->fetch_assoc()) {
        $id = $data['id'];
        $username = $data['username'];
        $email = $data['email'];
        $img = "asset/image/profile-picture/" . $data['image_url'];
    }
} else {
    $detail = getUserDetail($_SESSION['id']);
    while ($data = $detail->fetch_assoc()) {
        $id = $data['id'];
        $username = $data['username'];
        $email = $data['email'];
        $img = "asset/image/profile-picture/" . $data['image_url'];
    }
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
    <link rel="stylesheet" href="./style/css/profile.css">
    <link rel="stylesheet" href="./style/css/header.css">
    <link rel="shortcut icon" href="./asset/icon/favicon.png" type="image/x-icon" />
    <script src="./script/jquery-3.6.0.js"></script>
</head>

<body>
    <?php
    include './component/header.php';
    ?>
    <div class="app-content">
        <div class="information-container">
            <div class="profile-image">
                <img src="<?= $img ?>">
            </div>
            <div class="profile-username">
                <!-- Show username -->
                <?= $username ?>
            </div>
            <?php if (isset($_SESSION['id']) && strcmp($_SESSION['id'], $id) == 0) { ?>
            <div class="button-container">
                <a class="button-item" href="/settings.php">Edit Profile</a>
            </div>
            <?php } ?>
        </div>
        <div class="board-container">
            <div class="board-btn-container">
                <div class="btn-wrapper">
                    <div class="btn sort-btn" onclick="$('#sort-menu').toggle()">
                        <svg height="20" width="20" viewBox="0 0 24 24">
                            <path
                                d="M9 19.5a1.75 1.75 0 1 1 .001-3.501A1.75 1.75 0 0 1 9 19.5M22.25 16h-8.321c-.724-2.034-2.646-3.5-4.929-3.5S4.795 13.966 4.071 16H1.75a1.75 1.75 0 0 0 0 3.5h2.321C4.795 21.534 6.717 23 9 23s4.205-1.466 4.929-3.5h8.321a1.75 1.75 0 0 0 0-3.5M15 4.5a1.75 1.75 0 1 1-.001 3.501A1.75 1.75 0 0 1 15 4.5M1.75 8h8.321c.724 2.034 2.646 3.5 4.929 3.5s4.205-1.466 4.929-3.5h2.321a1.75 1.75 0 0 0 0-3.5h-2.321C19.205 2.466 17.283 1 15 1s-4.205 1.466-4.929 3.5H1.75a1.75 1.75 0 0 0 0 3.5">
                            </path>
                        </svg>
                    </div>
                    <div class="dropdown-menu" id="sort-menu">
                        <div class="title">
                            Sort boards by
                        </div>
                        <div class="menu-item">
                            <a href="">Name Ascending</a>
                        </div>
                        <div class="menu-item">
                            <a href="">Name Descending</a>
                        </div>
                        <div class="menu-item">
                            <a href="">Last Saved to</a>
                        </div>
                    </div>
                </div>
                <div class="btn-wrapper">
                    <div class="btn create-btn" onclick="$('#create-menu').toggle()">
                        <svg height="20" width="20" viewBox="0 0 24 24">
                            <path d="M22 10h-8V2a2 2 0 0 0-4 0v8H2a2 2 0 0 0 0 4h8v8a2 2 0 0 0 4 0v-8h8a2 2 0 0 0 0-4">
                            </path>
                        </svg>
                    </div>
                    <div class="dropdown-menu" id="create-menu">
                        <div class="title">
                            Create
                        </div>
                        <div class="menu-item">
                            <a href="./create-pin.php">Pin</a>
                        </div>
                        <div class="menu-item">
                            <a href="./create-board.php">Board</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="board-content">
                <?php
                $boards = getUserBoardAndPin($id);
                while ($board = $boards->fetch_assoc()) {
                    $preview = explode("|", $board['preview']);
                ?>
                <a href="board.php?id=<?= $board['id'] ?>" class="board-card">
                    <div class="board-images">

                        <!-- 
                            use video for video type
                            use image for image type
                            use div placeholder for empty preview 
                        -->
                        <div class="board-overlay"></div>
                        <div class="left-preview">
                            <!-- <video autoplay loop muted src=[URL]></video> -->
                            <?php if ($board['preview'] && array_key_exists(0, $preview)) { ?>
                            <img src=<?= "asset/image/pin/" . $preview[0] ?>>
                            <?php } else { ?>
                            <div class=placeholder></div>
                            <?php } ?>
                        </div>
                        <div class="right-preview">
                            <!-- <video autoplay loop muted src=[URL]></video> -->
                            <?php if (array_key_exists(1, $preview)) { ?>
                            <img src=<?= "asset/image/pin/" . $preview[1] ?>>
                            <?php } else { ?>
                            <div class=placeholder></div>
                            <?php } ?>
                            <?php if (array_key_exists(2, $preview)) { ?>
                            <img src=<?= "asset/image/pin/" . $preview[2] ?>>
                            <?php } else { ?>
                            <div class=placeholder></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="board-information">
                        <div class="board-title">
                            <!-- Show board name -->
                            <?= $board['name'] ?>
                        </div>
                        <div class="board-pins">
                            <!-- Show board total pin -->
                            <?= $board['total_pins'] ?>
                        </div>
                    </div>
                </a>
                <?php } ?>
            </div>
        </div>
    </div>
</body>

</html>