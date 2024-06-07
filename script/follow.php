<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Content-Type: application/json");
    echo json_encode(array("success" => false, "message" => "User is not logged in"));
    exit;
}
    
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["target"]) && isset($_POST["type"])) {
        $target = $_POST["target"];
        $type = $_POST["type"];
        $username = $_SESSION["username"];
        $subsfile = $_SERVER["DOCUMENT_ROOT"]."/users/".$username."/subs"; 
        $blocksfile = $_SERVER["DOCUMENT_ROOT"]."/users/".$username."/blocks";

        if(!file_exists($subsfile)) {
            touch($subsfile);
        }

        if(!file_exists($blocksfile)) {
            touch($blocksfile);
        }

        // This used to be FOUR different PHP scripts. At least I remembered to merge them.
        switch ($type) {
            case "follow":
                // Prevent following if blocked
                $blocklist = file_get_contents("../users/".$target."/blocks");
                if (stripos($blocklist, $_SESSION["username"] === false)) {
                    $lines = file($subsfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    $lines2 = file($blocksfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    if (in_array($target, $lines)) {
                        $return = array("success" => false, "message" => "User is already subscribed.");
                    } else if (in_array($target, $lines2)) {
                        $return = array("success" => false, "message" => "User is blocked.");
                    } else {
                        file_put_contents($subsfile, $target.PHP_EOL, FILE_APPEND);
                        $return = array("success" => true);
                    }
                }
                break;

            case "unfollow":
                $lines = file($subsfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                if (in_array($target, $lines)) {
                    $lines = array_diff($lines, [$target]);
                    file_put_contents($subsfile, implode(PHP_EOL, $lines) . PHP_EOL);
                    $return = array("success" => true);
                } else {
                    $return = array("success" => false, "message" => "User is not subscribed");
                }
                break;

            case "block":
                $lines = file($blocksfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                if (in_array($target, $lines)) {
                    $return = array("success" => false, "message" => "User is already blocked.");
                } else {
                    file_put_contents($blocksfile, $target.PHP_EOL, FILE_APPEND);
                    $return = array("success" => true);
                }
                // If we block someone, remember to unfollow them !
                $lines = file($subsfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                if (in_array($target, $lines)) {
                    $lines = array_diff($lines, [$target]);
                    file_put_contents($subsfile, implode(PHP_EOL, $lines) . PHP_EOL);
                }
                // Also remove chats
                unlink("../chats/" . min($username, $target) . "-" . max($username, $target) . ".json");
                break;

            case "unblock":
                $lines = file($blocksfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                if (in_array($target, $lines)) {
                    $lines = array_diff($lines, [$target]);
                    file_put_contents($blocksfile, implode(PHP_EOL, $lines) . PHP_EOL);
                    $return = array("success" => true);
                } else {
                    $return = array("success" => false, "message" => "User is not blocked");
                }
                break;
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
