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
    <p id="saveStatus"></p>
    <?php
    echo("<h1>Welcome, " . $_SESSION["username"] . " !</h1>"); 
    ?>
    <form id="settingsForm" onsubmit="event.preventDefault(); saveSettings();">
        <input type="hidden" id="username" value="<?php echo $_SESSION['username']; ?>">
    </form>
</body>
</html>
