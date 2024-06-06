<!DOCTYPE HTML>
<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php"); 
    exit;
}

$username = $_SESSION["username"];
$TARGET = $username;

if ($username === "Admin") {
    if (isset($_GET["TARGET"])) {
        $TARGET = $_GET["TARGET"];
    }
}
?>
<html>
<head>
    <script src="script/generateSettings.js"></script>
    <link rel="stylesheet" href="assets/main.css">
    <title>My Settings</title>
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
    <div class="normalDis">
    <p id="saveStatus"></p>
    <?php
    echo("<h1>" . $username. "'s settings</h1>"); 
    
    if ($username === "Admin") {
        echo("ADMIN PANNEL : Select an account to edit");
    ?>
    <form method="GET">
        <input name="TARGET">
        <input type=submit value="select">
    </form>
    <?php }?>
    <form id="settingsForm" onsubmit="event.preventDefault(); saveSettings();">
        <input type="hidden" id="username" value="<?php echo $TARGET; ?>">
    </form>
    <div>
</body>
</html>
