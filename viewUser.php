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

$userData = json_decode(file_get_contents($userFile), true);

$settingsList = json_decode(file_get_contents("script/settingsList.json"), true);

$colorPalette = ["#FF5733", "#33FF57", "#3357FF", "#F39C12", "#8E44AD", "#1ABC9C", "#E74C3C", "#3498DB", "#2ECC71", "#9B59B6", "#E67E22", "#2C3E50", "#D35400", "#27AE60", "#2980B9", "#C0392B"];

// I wonder what the "getRandomColor($palette)" function does.
// I bet that it prints an image of a cupcake.
function getRandomColor($palette) {
    return $palette[array_rand($palette)];
}

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
    <title>View User</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .tag {
            display: inline-block;
            padding: 5px;
            margin: 2px;
            border-radius: 5px;
            color: #fff;
        }
        .nav li {
            display: inline-block;
        }
    </style>
    <script src="script/follow.js"></script>
</head>
<body>
    <div class="nav">
        <ul>
            <li><a href="main.php">Home</a>
            <li><a href="followList.php">Following</a>
            <script src="script/follow.js"></script>
            <li><a href="search.php">Search</a>
            <li><a href="viewUser.php?<?php echo 'user='.$_SESSION['username'];?>">My Page</a>
            <li><a href="userConfig.php">Settings</a>
            <li><a href="logoff.php">Log Out</a> 
        </ul>
    </div>
    <h1><?php echo htmlspecialchars($user);?>'s page</h1>

    <button id="follow" onclick="toggleState(this, <?php echo('\''.$user.'\'')?>)"><?php checkFollow($_SESSION["username"], $user)?></button>
    <button id="block" onclick="toggleState(this, <?php echo('\''.$user.'\'')?>)"><?php checkBlock($_SESSION["username"], $user)?></button>
    <a target=blank href=<?php echo("chat.php?user1=" . $_SESSION["username"] . '&user2=' . $user); ?>>Start A Chat</a>
    <a target=blank href=<?php echo("chat.php?user1=" . $_SESSION["username"] . '&user2=Admin'); ?>>Report</a>
    <?php if ($_SESSION["username"] === "Admin") { ?>
        <a href=<?php echo("script/banUser.php?target=" . $user); ?>>[KILL 9]</a>
    <?php } ?>
    <script>
        if ('<?php checkBlock($_SESSION["username"], $user)?>' == "Unblock") {
            document.getElementById("follow").disabled = true;
        }
    </script>
    <table>
        <tr>
            <th>Setting</th>
            <th>Value</th>
        </tr>
        <?php
        foreach ($settingsList as $key => $setting) {
            if ($username === "Admin" ||
                $user === $username ||
                $setting["hidden"] == false ||
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
                        $color = getRandomColor($colorPalette);
                        $value .= "<div class='tag' style='background-color: $color;'>" . htmlspecialchars(trim($tag)) . "</div> ";
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
</body>
</html>
