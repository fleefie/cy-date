<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Content-Type: application/json");
    echo json_encode(array("success" => false, "message" => "User is not logged in"));
    exit;
}
    
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["target"])) {
        $target = $_POST["target"];
        $username = $_SESSION["username"];
        $subsfile = $_SERVER["DOCUMENT_ROOT"]."/users/".$username."/subs"; 
        
        if(!file_exists($subsfile)) {
            touch($subsfile);
        }
        $lines = file($subsfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (in_array($target, $lines)) {
            $return = array("success" => false, "message" => "User is already subscribed.");
        } else {
            file_put_contents($subsfile, $target.PHP_EOL, FILE_APPEND);
            $return = array("success" => true);
        }

        header('Content-Type: application/json');
        echo json_encode($return);
        exit;
    } else {
        echo json_encode(array("success" => false, "message" => "Target not provided."));
        exit;
    }
} else {
    echo json_encode(array("success" => false, "message" => "Bad request, stop trying to hack me or fix your code."));
    exit;
}
?>
