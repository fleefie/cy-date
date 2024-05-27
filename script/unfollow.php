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
        header('Content-Type: application/json');
        
        if(!file_exists($subsfile)) {
            touch($subsfile);
            echo json_encode(array("success" => false, "message" => "User is not subscribed"));
            exit;
        }
        $lines = file($subsfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (in_array($target, $lines)) {
            $lines = array_diff($lines, [$target]);
            file_put_contents($subsfile, implode(PHP_EOL, $lines) . PHP_EOL);
            $return = array("success" => true);
        } else {
            $return = array("success" => false, "message" => "User is not subscribed");
        }

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
