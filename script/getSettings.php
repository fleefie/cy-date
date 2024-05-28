<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

$settingsfile = $_SERVER["DOCUMENT_ROOT"] . "/users/" . $_SESSION["username"] . "/user.json";
if (!file_exists($settingsfile)) {
    echo json_encode(array());
    exit;
}

$settings = file_get_contents($settingsfile);

header("Content-Type: application/json");
echo $settings;
?>
