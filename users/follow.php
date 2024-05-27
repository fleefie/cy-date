<?php 
session_start();
if (!isset($_SESSION['username'])){
    header('Location: login.php'); 
    exit;


if($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentuser = $_SESSION['username'];
    $target = $_POST['username'];
}

$userake = "users/".$currentuser;
if (!is_dir($userake)) {
    mkdir($userake, 0777, true);
}
$subsfile = $userake."/subs.txt";

file_put_contents($subsfile, $target.PHP_EOL, FILE_APPEND);
echo "From now on, you are subbed to ".$target;
}
?>

<!DOCTYPE html>
    <head>
    <title> Follow </title>
    </head>
    <body>   
    </body>
    </html>

