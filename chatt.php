<?php
session_start();
if (!isset($_SESSION['username'])){
  header("Location: login.php");
  exit;
}

$currentUser = $_SESSION['username'];
$currentUserId = $_SESSION['user_id'];
$chatUser = isset($_GET['user']) ? $_GET['user'] : null;
$chatUserId = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($chatUser === null || $chatUserId === null){
  echo "non specified user";
  exit;
}

$chatFile = $currentUserId < $chatUserId ? "chat/$currentUserId-$chatUserId.json" : "chat/$chatUserId-$currentUserId.json";
$messages = [];
if (file_exists($chatFile)){
  $messages = json_decode(file_get_contents($chatFile), true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  $message = [
    "from" => $currentUser,
    "to" => $chatUser,
    "message" => $_POST['message']
    trim($_POST['message']),
    "timestamp" => time()
  
  ];
  $messages[] = $message;
  file_put_contents($chatFile, json_encode($messages, JSON_PRETTY_PRINT));
  header("Location: chatt.php?user=$chatUser&id=$chatUserId");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content=width=device-width, initial-scale=1.0">
  <title>Chat with <?php echo htmlspecialchars($chatuser); ?></title>
</head>
<body>
  <h1>Chat with <?php echo htmlspecialchars($chatuser); ?></h1>
  <div>
    <?php foreach($messages as $message): ?>
      <p>
        <strong><?php echo htmlspecialchars($message['from']); ?>:</strong>
        <?php echo htmlspecialchars($message['message']); ?>
        <small><?php echo date("Y-m-d H:i:s", $message['timestamp']); ?></small>
        </p>
          <?php endforeach; ?>
  </div>
  <form action="<php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?user=" . urlencode($chatUser) . "&id=" . urlencode($chatUserId)); ?>" method="post">
     <textarea name="message" rows="4" cols="50"></textarea>
     <br>
     <button type="submit">Send</button>
  </form>
          <p><a href="users.php"> Return to the list</a></p>
</body>
</html>
  
