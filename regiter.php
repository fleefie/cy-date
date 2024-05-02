<?php
session_start();
if (isset($_SESSION['username'])) {
    header('Location: login.php'); 
    exit;
}
if($_SERVER["REQUEST_METHOD"]=="POST") {
    if(empty(trim($_POST)))
}











>
