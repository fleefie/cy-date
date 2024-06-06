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
<link rel="stylesheet" href="assets/main.css">
<title>Home</title>
</head>
<body>
    <header>
        <img src="assets/logo_H.png">
        <nav class="navigation">
            <div class="icondiv">
            <a href="main.php"><img class="icon" src="assets/icons/home-outline.svg"></a>
            <a href="search.php"><img class="icon" src="assets/icons/search-outline.svg"></a>
            <a href="followList.php"><img class="icon" src="assets/icons/people-outline.svg"></a>
            <a href="viewUser.php?<?php echo 'user='.$_SESSION['username'];?>"><img class="icon" src="assets/icons/person-outline.svg"></a>
            <a href="userConfig.php"><img class="icon" src="assets/icons/cog-outline.svg"></a>
            <button class="btnLogout" onclick="window.location.href='logoff.php';">Log out</button>
            </div>
        </nav>
    </header>
    <form method="GET" action="search.php">
    <div class="recherche">
        <input type="text" name="query" placeholder="Search for tags or users">
    </div>
    </form>
    <div class="box">
        <p> Our 15 latest users :
        <br>
        <?php
            $lines = file("users/userlist", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $user) {
                echo("<a href='viewUser.php?user=".$user."'>".$user."</a><br>");
            }
        ?>
    </div>
</body>