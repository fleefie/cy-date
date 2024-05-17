<!-- TODO : CLEAN THIS MESS UP!!!! -->
<?php
session_start();
if (isset($_SESSION["loggedin"])) {
    header("Location: main.php"); 
    exit;
}
?>

<html>
    <head>
        <script>
            function toggleRegister(checkbox) {
                var loginBtn = document.getElementById("loginBtn");
                var registerBtn = document.getElementById("registerBtn");
                var registerToggle = document.getElementById("registerToggle");

                if (checkbox.checked) {
                    registerDiv.style.display = "block";
                    loginBtn.style.display = "none";
                    loginBtn.disabled = true;
                    registerBtn.disabled = false;
                } else {
                    registerDiv.style.display = "none";
                    loginBtn.style.display = "block";
                    loginBtn.disabled = false;
                    registerBtn.disabled = true;
                }
            }
        </script>
    </head>
    <body>
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
                    if (is_dir("users/" . $username) && password_verify($password, file_get_contents($userPrefix . "/pass"))) {
                        $_SESSION["loggedin"] = true;
                        $_SESSION["username"] = $username;
                        header("Location: main.php");
                        exit;
                    } else {
                        $badLogin = true;
                    }
                }
                // Register
                if (isset($_POST["register"])) {
                    if (!is_dir($userPrefix)) {
                        if ($_POST["password"] === $_POST["password_confirm"]) {
                            $hashedPass = password_hash($password, PASSWORD_DEFAULT);
                            mkdir($userPrefix);
                            file_put_contents("users/" . $username . "/pass" , $hashedPass, FILE_APPEND);
                            $userCreated = true;
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
        <h1>Welcome to CY-Date</h1>
        
        <?php if ($badInput) { ?>
            <p>Error: Username and password must be alphanumeric.</p>
        <?php } ?>
        <?php if ($badLogin) { ?>
            <p>Error: Invalid username or password.</p>
        <?php } ?>
        <?php if ($userExists) { ?>
            <p>Error: User already exists.</p>
        <?php } ?>
        <?php if ($passwordMismatch) { ?>
            <p>Error: Passwords do not match.</p>
        <?php } ?>
        <?php if ($userCreated) { ?>
            <p>User successfully registered.</p>
        <?php } ?>

        <form action="" method="POST">
            User :<input type="text" name="username"><br>
            Pass :<input type="Password" name="password"><br>
            <input type=submit name="login" value="Login" id="loginBtn">
            <div id="registerDiv" style="display: none;">
                Confirm Pass: <input type="password" name="password_confirm"><br>
                <input type=submit name="register" value="Register" id="registerBtn" disabled>
            </div>
            <label><input type="checkbox" onchange="toggleRegister(this)"> Register</label>
        </form>
    </body>
</html>
