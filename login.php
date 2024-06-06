<!DOCTYPE html>

<?php
session_start();
if (isset($_SESSION["loggedin"])) {
    header("Location: main.php"); 
    exit;
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login_Heartbeat</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="assets/login/style.css">
</head>
<body>

    <!--Page d'accueil pour le login/register-->

    <!-- Je suis désolé Camille mais je doit tarnir ton HTML pour faire rentrer mon PHP dégeu :( -->
    <?php
        $badInput = false;
        $badLogin = false;
        $userExists = false;
        $userCreated = false;
        $passwordMismatch = false;

        if (isset($_POST["username"]) && isset($_POST["password"])) {
            $username = $_POST["username"];
            $password = $_POST["password"];

            if (ctype_alnum ($username) && ctype_alnum($password)) {
                $userPrefix = "users/" . $username;
                // Login
                if (isset($_POST["login"])) {
                    if (is_dir("users/" . $username) && password_verify($password, explode(":",file_get_contents($userPrefix . "/pass"))[0])) {
                        $_SESSION["loggedin"] = true;
                        $_SESSION["username"] = $username;
                        header("Location: main.php");
                        exit;
                    } else {
                        $badLogin = true;
                    }
                }
                // Register
                if (isset($_POST["register"]) && isset($_POST["email"])) {
                    if (!is_dir($userPrefix)) {
                        if ($_POST["password"] === $_POST["password_confirm"]) {
                            $hashedPass = password_hash($password, PASSWORD_DEFAULT);
                            mkdir($userPrefix);
                            file_put_contents("users/" . $username . "/pass" , $hashedPass . ":" . $_POST["email"], FILE_APPEND);
                            $_SESSION["loggedin"] = true;
                            $_SESSION["username"] = $username;
                            $usernames = [];
                            if (file_exists("users/userlist")) {
                                $usernames = file("users/userlist", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                            }
                            array_push($usernames, $username);
                            $usernames = array_slice($usernames, -15);
                            file_put_contents("users/userlist", implode("\n", $usernames));
                            touch("users/".$username."/subs");
                            touch("users/".$username."/blocks");
                            header("Location: userConfig.php");
                            exit;
                        } else {
                            $passwordMismatch = true;
                        }
                    } else {
                        $userExists = true;
                    }
                }
            } else {
                $badInput = true;
            }
        }

        // Flush $_POST or else PHP craps itself
        unset($_POST["username"]);
        unset($_POST["password"]);
        unset($_POST["password_confirm"]);
    ?>

    <header>
        <img src="assets/login/logo_H.GIF">
        <nav class="navigation">
            <a href="profil.html">About</a>
            <button class="btnLogin">Login</button>
        </nav>
    </header>

    <!--Petite description -->

    <div class="description">
        <h1>THE RIGHT SITE TO FIND THE RIGHT PERSON</h1>
        <h3>Why don't you try ?</h3>
    </div>

        <div class="wrapper">
            <span class="icon-close"><ion-icon name="close-outline"></ion-icon></span>


            <!--Login-->

            <div class="form-box login">
                <h2>Login</h2>
                <form action="" method="POST">
                    <div class="input-box">
                        <span class="icon"><ion-icon name="person-outline"></ion-icon></span>
                        <input type="username" name="username" required>
                        <label>Username</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
                        <input type="password" name="password" required>
                        <label>Password</label>
                    </div>
                    <button type="submit" name="login" value="Login" class="btn">Login</button>
                    <div class="login-register">
                        <!-- Encore désolé, on fait rentrer les codes d'erreur ici ! -->
                        <?php if ($badInput) { ?>
                            <p>Error: Username and password must be alphanumeric.</p></br>
                        <?php } ?>
                        <?php if ($badLogin) { ?>
                            <p>Error: Invalid username or password.</p></br>
                        <?php } ?>
                        <p>Don't have an account ?
                        <a href="#" class="register-link">Register now</a></p>

                    </div>
                </form>

            </div>

            <!--Register-->

            <div class="form-box register">
                <h2>Registration</h2>
                <form action="" method="POST">
                    <div class="input-box">
                        <span class="icon"><ion-icon name="person-outline"></ion-icon></span>
                        <input type="text" name="username" required>
                        <label>Username</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><ion-icon name="mail-outline"></ion-icon></span>
                        <input type="mail" name="email" required>
                        <label>E-Mail</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
                        <input type="password" name="password" required>
                        <label>Password</label>
                    </div>
                    <div class="input-box">
                        <input type="password" name="password_confirm" required>
                        <label>Comfirm Password</label>
                    </div>
                    <button type="submit" name="register" value="Register" class="btn">Register</button>
                    <div class="login-register">
                        <?php if ($badInput) { ?>
                            <p>Error: Username and password must be alphanumeric.</p></br>
                        <?php } ?>
                        <?php if ($userExists) { ?>
                            <p>Error: User already exists.</p></br>
                        <?php } ?>
                        <?php if ($passwordMismatch) { ?>
                            <p>Error: Passwords do not match.</p></br>
                        <?php } ?>
                        <p>Already have an account ?
                            <a href="#" class="login-link">Login</a></p>
                    </div>
                </form>

            </div>
        </div>

    <script src="assets/login/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>