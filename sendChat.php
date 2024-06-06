<?php
session_start();
if (!isset($_SESSION["username"])) {
    echo json_encode(["success" => false, "error" => "Not logged in"]);
    exit;
}

$user1 = $_POST['user1'];
$user2 = $_POST['user2'];
$messageContent = $_POST['message'];

$chatFile = "chats/" . min($user1, $user2) . "-" . max($user1, $user2) . ".json";

$chatData = json_decode(file_get_contents($chatFile), true);
$newMessage = [
    "content" => $messageContent,
    "sender" => $_SESSION["username"],
    "date" => date("d-m H:i:s")
];
$chatData[] = $newMessage;
file_put_contents($chatFile, json_encode($chatData));

echo json_encode(["success" => true]);
