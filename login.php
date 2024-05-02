<?php
session_start();
if (isset($_SESSION['loggedin'])) {
    header('Location: main.php'); 
    exit;
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $users = array(
        'KHEFY' => 'Charles',
        'Camelia' => 'Leclerc'
  );

    if (array_key_exists($username, $users) && $users[$username] == $password) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header('Location: main.php');
        exit;
    } else {
       header("Location: index.html");
    }
}
?>