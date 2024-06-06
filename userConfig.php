<!DOCTYPE HTML>
<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php"); 
    exit;
}

$username = $_SESSION["username"];
?>
<html>
<head>
    <script src="script/generateSettings.js"></script>
    <style>
        .nav li {
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="nav">
        <ul>
            <li><a href="main.php">Home</a>
            <li><a href="search.php">Search</a>
            <li><a href="followList.php">Followed</a>
            <li><a href="viewUser.php?<?php echo 'user='.$_SESSION['username'];?>">My Page</a>
            <li><a href="logoff.php">Log Out</a> 
        </ul>
    </div>
    <p id="saveStatus"></p>
    <?php
    echo("<h1>Welcome, " . $username. " !</h1>"); 
    ?>
    <form id="settingsForm" onsubmit="event.preventDefault(); saveSettings();">
        <input type="hidden" id="username" value="<?php echo $username; ?>">
    </form>
</body>
</html>
