<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php"); 
    exit;
}

$settingsfile = $_SERVER["DOCUMENT_ROOT"] . "/users/" . $_SESSION["username"] . "/user.json";
if (!file_exists($settingsfile)) {
    touch($settingsfile);
}

if ($_SESSION["username"] === "Admin") {
    if(isset($_GET["target"])) {
        $settingsfile = $_SERVER["DOCUMENT_ROOT"] . "/users/" . $_GET["target"] . "/user.json";
        if (!file_exists($settingsfile)) {
        touch($settingsfile);
        }
    }
}

$input = file_get_contents('php://input');
$settings = json_decode($input, true);

$validSettings = [
    "description" => "text",
    "gender" => ["Male", "Female", "Not Specified"],
    "tags" => "text",
    "name" => "text",
    "email" => "text",
    "age" => ["18-22", "23-28", "28-38", "38-48", "48+"],
    "region" => ["Europe", "Asia", "Oceania", "Africa", "North America", "South America"],
];

// This is JUST verification. Holy.
foreach ($settings as $key => $value) {
    // If the setting doesn't exist, or if it exists and has an invalid value and is not a text field, error out
    // Holy spaghetti
    // Todo : fix this.
    if (!array_key_exists($key, $validSettings)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "$key is an invalid setting."]);
        exit;
    }

    if ($validSettings[$key] === "text") {
        // TODO : ADD TEXT VERIFICATION!!!!!!!!!!!!! DUMBASS!!!!!
        if (!is_string($value)) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "$value for $key is not a string."]);
            exit;
        }
    } else {
        if (!in_array($value, $validSettings[$key])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "$value is an invalid value for $key."]);
            exit;
        }
    }
}

$jsonSettings = json_encode($settings, JSON_PRETTY_PRINT);
file_put_contents($settingsfile, $jsonSettings)
?>
