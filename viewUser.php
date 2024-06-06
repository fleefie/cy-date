<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET["user"])) {
    echo "No user specified.";
    exit;
}

$username = $_SESSION["username"];
$user = $_GET["user"];
$userFile = "users/$user/user.json";

if (!file_exists($userFile)) {
    echo "User not found.";
    exit;
}

$viewFile = "users/$user/viewlist";
if (!file_exists($viewFile)) {
    touch($viewFile);
}

$viewList = file($viewFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if (!in_array($username, $viewList)) {
    $viewList[] = $username;
    file_put_contents($viewFile, implode(PHP_EOL, $viewList));
}

$userData = json_decode(file_get_contents($userFile), true);

$settingsList = json_decode(file_get_contents("script/settingsList.json"), true);


// This feels hacky and efficient at once.
function checkFollow($username, $target) {
    $subsFile = "users/$username/subs";
    if (file_exists($subsFile)) {
        $lines = file($subsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (in_array($target, $lines)) {
            echo "Unfollow";
        } else {
            echo "Follow";
        }
    }
}

function checkFollowNoPrint($username, $target) {
    $subsFile = "users/$username/subs";
    if (file_exists($subsFile)) {
        $lines = file($subsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (in_array($target, $lines)) {
            return "True";
        } else {
            return "False";
        }
    }
}

function checkBlock($username, $target) {
    $blocksFile = "users/$username/blocks";
    if (file_exists($blocksFile)) {
        $lines = file($blocksFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (in_array($target, $lines)) {
            echo "Unblock";
        } else {
            echo "Block";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($user);?>'s page</title>
    <script src="script/follow.js"></script>
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
    <div class="normalDis">
    <h1><?php echo htmlspecialchars($user);?>'s page</h1>

    <button class="btn" id="follow" onclick="toggleState(this, <?php echo('\''.$user.'\'')?>)"><?php checkFollow($_SESSION["username"], $user)?></button>
    <button class="btn" id="block" onclick="toggleState(this, <?php echo('\''.$user.'\'')?>)"><?php checkBlock($_SESSION["username"], $user)?></button>
    <button class="btn" onclick='window.location.href=<?php echo("\"chat.php?user1=" . $_SESSION["username"] . "&user2=" . $user . "\""); ?>'>Start A Chat</button>
    <button class="btn" onclick='window.location.href=<?php echo("\"chat.php?user1=" . $_SESSION["username"] . "&user2=Admin\""); ?>'>Report</button>
    <?php if ($_SESSION["username"] === "Admin") { ?>
        <a href=<?php echo("script/banUser.php?target=" . $user); ?>>[KILL 9]</a>
    <?php } ?>
    <script>
        if ('<?php checkBlock($_SESSION["username"], $user)?>' == "Unblock") {
            document.getElementById("follow").disabled = true;
        }
    </script>
    <table>
        <?php
        foreach ($settingsList as $key => $setting) {
            if ($username === "Admin" ||
                $user === $username ||
                $setting["hidden"] == "False" ||
                (checkFollowNoPrint($user, $username) === "True" && 
                checkFollowNoPrint($username, $user) === "True")) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($setting["label"]) . "</td>";
                
                // Just some parsing
                if ($setting["type"] === "checkbox") {
                    $value = $userData[$key] ? "Yes" : "No";
                } elseif ($key === "tags") {
                    $tags = explode(",", $userData[$key]);
                    $value = "";
                    foreach ($tags as $tag) {
                        $value = $value. "<div class='tag'>" . htmlspecialchars(trim($tag)) . "</div> ";
                    }
                } else {
                    $value = $userData[$key];
                }
                
                echo "<td>" . $value . "</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>
    <h1>Who saw my profile ?</h1>
    <ul>
        <?php
            foreach ($viewList as $viewer) {
                echo "<li><a href=viewUser.php?user=" . htmlspecialchars($viewer) . ">" . htmlspecialchars($viewer) . "</a></li>";
            }
        ?>
    </ul>
    </div>
</body>
</html>
