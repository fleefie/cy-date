<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); 
    exit;
}

if (!is_file("users/".$_SESSION['username']."/user")) {
    header('Location: userConfig.php');
    exit;    
}

//session_destroy();
?>