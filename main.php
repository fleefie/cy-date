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
    <style>
        .nav li {
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="nav">
        <ul>
            <li><a href="search.php">Search</a>
            <li><a href="followList.php">Followed</a>
            <li><a href="viewUser.php?<?php echo 'user='.$_SESSION['username'];?>">My Page</a>
            <li><a href="userConfig.php">Settings</a>
            <li><a href="logoff.php">Log Out</a> 
        </ul>
    </div>
    <h1><3Beats</h1>

    <form method="GET" action="search.php">
        <input type="text" name="query" placeholder="Search for a tag or username">
        <button type="submit">Find Your Mate</button>
    </form>

    <p> Our 25 latest users :
    <br>
    <?php
        $lines = file("users/userlist", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $user) {
            echo("<a href='viewUser.php?user=".$user."'>".$user."</a><br>");
        }
    ?>
</body>