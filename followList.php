<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION["username"];
$subsFile = "users/$username/subs";
$blocksFile = "users/$username/blocks";
$chatsDir = "chats/";

$subscriptions = [];
$blocks = [];
$chats = [];

if (file_exists($subsFile)) {
    $follows = file($subsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

if (file_exists($blocksFile)) {
    $blocks = file($blocksFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}


if ($handle = opendir($chatsDir)) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." && pathinfo($entry, PATHINFO_EXTENSION) === "json") {
            // Basename prends le nom de fichier sans son chemin
            list($user1, $user2) = explode('-', basename($entry, ".json"));
            if ($user1 === $username || $user2 === $username) {
                // Ternary Ternary very very scary spooky !
                $chats[] = $user1 === $username ? $user2 : $user1;
            }
        }
    }
    closedir($handle);
}

?>

<!DOCTYPE html>
<head>
    <title>My Follows and Blocks</title>
    <link rel="stylesheet" href="assets/main.css">
</head>
<body>
    <header>
        <img src="assets/logo_H.png">
        <nav class="navigation">
            <div class="icondiv">
            <a href="main.php"><img class="icon" src="assets/icons/home-outline.svg"></a>
            <a href="search.php"><img class="icon" src="assets/icons/search-outline.svg"></a>
            <a href="followList.php"><img class="icon" src="assets/icons/people-outline.svg"></a>
            <a href="viewUser.php?<?php echo 'user='.$_SESSION['username'];?>"><img class="icon" src="assets/icons/person-outline.svg"></a>
            <a href="userConfig.php"><img class="icon" src="assets/icons/cog-outline.svg"></a>
            <button class="btnLogout" onclick="window.location.href='logoff.php';">Log out</button>
            </div>
        </nav>
    </header>
    <table class="box">
    <tr>
        <th>You are following</th>
        <th>Open Chats</th>
        <th>Blocked Users</th>
    </tr>
    <tr>
    <td>
        <ul>
        <?php
        if (!empty($follows)) {
            foreach ($follows as $sub) {
                echo "<li><a href=viewUser.php?user=" . htmlspecialchars($sub) . ">" . htmlspecialchars($sub) . "</a></li>";
            }
        } else {
            echo "<li>No one</li>";
        }
        ?>
        </ul>
    </td>
    <td>
    <ul>
        <?php
        if (!empty($chats)) {
            foreach ($chats as $chat) {
                echo "<li><a target=blank href=chat.php?user1=" . $_SESSION["username"] . "&user2=" . htmlspecialchars($chat) . ">" . htmlspecialchars($chat) . "</a></li>";
            }
        } else {
            echo "<li>None</li>";
        }
        ?>
    </ul>
    </td>
    <td>
    <ul>
        <?php
        if (!empty($blocks)) {
            foreach ($blocks as $block) {
                echo "<li><a href=viewUser.php?user=" . htmlspecialchars($block) . ">" . htmlspecialchars($block) . "</a></li>";
            }
        } else {
            echo "<li>No one</li>";
        }
        ?>
    </ul>
    </td>
    </tr>
    </table>
</body>
</html>
