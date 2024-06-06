<?php

session_start();

if(!isset($_SESSION['username'])){
  header("Location: login.php");
  exit;
}

$users = [];
$usersDir = "users/";

foreach(scandir($usersDir) as $user){
  if($user !== "." || $user !== ".." && pathinfo($user, PATHINFO_EXTENSION) === "json") {
    $usersC = json_decode(file_get_contents($usersDir.$user), true);
    $users[]= $usersC;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content=width=device-width, initial-scale=1.0">
  <title>Users List</title>
</head>

<body>
  <h1>Users List</h1>
  <ul>
    <?php foreach($users as $user): ?>
    <?php if($user['username'] !== $_SESSION['username']) ?>
      <li>
        <?php echo htmlspecialchars($user['username']); ?>
        <a href="chatt.php?user=<?php echo $user['username']; ?>&id=<?php echo $user['user_id']; ?>"> Send a chatt</a>
      </li>
    <?php endif; ?>
    <?php endforeach; ?>
  </ul>
  
</body>
</html>
