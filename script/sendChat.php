<?php
session_start();
if (!isset($_SESSION["username"])) {
    echo json_encode(["success" => false, "error" => "Not logged in"]);
    exit;
}

$user1 = $_POST['user1'];
$user2 = $_POST['user2'];
$messageContent = $_POST['message'];

$otheruser = $user1 === $_SESSION["username"] ? $user2 : $user1;
$blocklist = file_get_contents("../users/".$otheruser."/blocks");
if (stripos($blocklist, $_SESSION["username"]) !== false) {
    echo json_encode(["success" => false]);
    exit;
}

$chatFile = "../chats/" . min($user1, $user2) . "-" . max($user1, $user2) . ".json";

$chatData = json_decode(file_get_contents($chatFile), true);
$newMessage = [
    "content" => $messageContent,
    "sender" => $_SESSION["username"],
    "date" => date("d-m H:i:s")
];
$chatData[] = $newMessage;
file_put_contents($chatFile, json_encode($chatData, JSON_PRETTY_PRINT));

echo json_encode(["success" => true]);
