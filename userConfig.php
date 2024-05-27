<!DOCTYPE HTML>
<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php"); 
    exit;
}
?>
<html>
<head>
    <script src="script/generateSettings.js"></script>
</head>
<body>
    <p id="saveStatus">
    <?php
    session_start(); 
    echo("<h1>Welcome, " . $_SESSION["username"] . " !</h1>"); 
    ?>
    <form id="settingsForm" onsubmit="event.preventDefault(); saveSettings();">
    
    </form>
</body>
</html>