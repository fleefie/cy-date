<?php

session_start();


if (!isset($_SESSION['loggedin'])) {

    header('Location: verification.php'); 

    exit;

} else {

    echo "Bonjour, ".$_SESSION['username'].". Vous êtes connecté.<br>";

}

?>