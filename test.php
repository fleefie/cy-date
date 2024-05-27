<?php
session_start();
if (!isset($_SESSION['username'])){
    header('Location: follow.php'); 
    exit;
}
$username = "Test";
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Test</title>
    </head>
    <body>
        <h1>Test</h1>
        <form action="follow.php" method="POST">
            <input type="text" name="username" value="<?php echo $username; ?>" >
          <input type="button" value = "Follow">
    </form>
  </body>
  </head>
</html>
      
