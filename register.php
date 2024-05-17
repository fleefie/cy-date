<?php
session_start();
if (isset($_SESSION['username'])) {
    header('Location: login.php'); 
    exit;
}
if($_SERVER["REQUEST_METHOD"]=="POST") {

    if(empty(trim($_POST["username"])) || empty(trim($_POST["password"])) || empty(trim($_POST["description"]))) {
        $error = "All fields are required";
    }
    else {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        $description = trim($_POST["description"]);

        if(file_exists("users/" . $username . ".txt")){
            $error = "Username already exists. Please choose another one.";
        }
        else {
            $userID = uniqid();
            $userData = $userID . "|" . $username . "|" . password_hash($password,PASSWORD_DEFAULT) . "|" . $description . PHP_EOL;

            file_put_contents("users/" . $username . ".txt" , $userData, FILE_APPEND);

            header("Location:login.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html> 
<html>
<head>
    <title>Inscription</title>
</head> 
<body> 
    <h2>Inscription</h2> 
    <form action="" method="post">
        <div>
            <label for="username"> Username :</label>
            <input type="text" name="username" id="username">
        </div> 
        <div>
            <label for="password"> Password :</label> 
            <input type="password" name="password" id="password"> 
        </div> 
        <div> 
            <label for="description"> Description :</label> 
            <textarea name="description" id="description" rows="3"></textarea>
        </div> 
        <div> 
            <input type="submit" value="Register">
        </div> 
    </form> 
    <p> Got an account ? <a href="login.php"> Login here</a> </p> 
</body> 
</html>
