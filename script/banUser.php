<?php
session_start();
if ($_SESSION["username"] !== "Admin") {
    header("Location: ../main.php");
    exit;
}

if (!isset($_GET["target"])) {
    echo "You must specify whom to kill.";
    exit;
}

$target = $_GET["target"];
$banlistFile = "../users/banlist";

if (!file_exists($banlistFile)) {
    file_put_contents($banlistFile, "");
}

$banlist = file_get_contents($banlistFile);
$userPass = "../users/" . $target . "/pass";
if (file_exists($userPass)) {
    $userPassContent = file_get_contents($userPass);
    list(, $email) = explode(":", $userPassContent);
    if (!empty($email)) {
        $banlist = $banlist . $email . PHP_EOL;
    }
}
file_put_contents($banlistFile, $banlist);

$userDir = "../users/" . $target;
if (file_exists($userDir)) {
    unlink($userDir."/blocks");
    unlink($userDir."/pass");
    unlink($userDir."/subs");
    unlink($userDir."/user.json");
    if (rmdir($userDir)) {
        echo "<h1>SIGKILL has been sent.</h1>";
    } else {
        echo "what";
    }
} else {
    echo "$targetUser not found.";
}
?>
