<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); 
    exit;
}

if (!is_file("users/".$_SESSION['username']."/user.json")) {
    header('Location: userConfig.php');
    exit;    
}

//session_destroy();
?>
<!DOCTYPE HTML>
<head>
</head>
<body>
    <h1><3Beats</h1>

    <form>
        <p>Search for a tag or username :
        <input type="text">
        <input type="submit" value="Search">
    </form>
    <p> Our 10 latest users :
    <br>
    <?php
        $lines = file("users/userlist", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $user) {
            echo("<a href='viewUser.php?user=".$user."'>".$user."</a><br>");
        }
    ?>
</body>