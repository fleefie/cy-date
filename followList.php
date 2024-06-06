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
    <title><?php echo htmlspecialchars($username); ?>'s Follows and Blocks</title>
    <style>
        .nav li {
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="nav">
        <ul>
            <li><a href="main.php">Home</a>
            <li><a href="search.php">Search</a>
            <li><a href="viewUser.php?<?php echo 'user='.$_SESSION['username'];?>">My Page</a>
            <li><a href="userConfig.php">Settings</a>
            <li><a href="logoff.php">Log Out</a> 
        </ul>
    </div>
    <h1>You are followsing :</h1>
    <ul>
        <?php
        if (!empty($follows)) {
            foreach ($follows as $sub) {
                echo "<li><a href=viewUser.php?user=" . htmlspecialchars($sub) . ">" . htmlspecialchars($sub) . "</a></li>";
            }
        } else {
            echo "<li>No one ! Go find people big guy !</li>";
        }
        ?>
    </ul>

    <h1>Open Chats</h1>
    <ul>
        <?php
        if (!empty($chats)) {
            foreach ($chats as $chat) {
                echo "<li><a target=blank href=chat.php?user1=" . $_SESSION["username"] . "&user2=" . htmlspecialchars($chat) . ">" . htmlspecialchars($chat) . "</a></li>";
            }
        } else {
            echo "<li>Wow, so very empty. Go make some noise !</li>";
        }
        ?>
    </ul>

    <h1>Blocked Users</h1>
    <ul>
        <?php
        if (!empty($blocks)) {
            foreach ($blocks as $block) {
                echo "<li><a href=viewUser.php?user=" . htmlspecialchars($block) . ">" . htmlspecialchars($block) . "</a></li>";
            }
        } else {
            echo "<li>No one. Blocking may be useful to you, don't hesitate to do it !</li>";
        }
        ?>
    </ul>
</body>
</html>
