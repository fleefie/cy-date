<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html'); 
    exit;
} else {
    echo "Bonjour, ".$_SESSION['username'].". Vous êtes connecté.<br>";
}

//session_destroy();
?>