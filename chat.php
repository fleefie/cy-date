<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

$user1 = $_GET['user1'];
$user2 = $_GET['user2'];

if (($_SESSION["username"] !== $user1 && $_SESSION["username"] !== $user2) && $_SESSION["username"] !== "Admin") {
    echo "Access denied.";
    exit;
}

$chatFile = "chats/" . min($user1, $user2) . "-" . max($user1, $user2) . ".json";
if (!file_exists($chatFile)) {
    file_put_contents($chatFile, json_encode([]));
}

$chatData = json_decode(file_get_contents($chatFile), true);
?>

<!DOCTYPE html>
<head>
    <script src="script/chat.js"></script>
    <link rel="stylesheet" href="assets/main.css">
    <script>
        window.onload = function() {
            var chatBox = document.getElementById("chatBox");
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    </script>
    <title>Chat with <?php echo ($user1 === $username ? $user1 : $user2) ?> </title>
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
    <input type="hidden" id="user1" value="<?php echo htmlspecialchars($user1); ?>">
    <input type="hidden" id="user2" value="<?php echo htmlspecialchars($user2); ?>">
    <div id="chatBox" class="chatbox">
        <?php foreach ($chatData as $index => $message): ?>
            <div>
                <span>[<a href=viewUser.php?user=<?php echo htmlspecialchars($message['sender']); ?>><?php echo htmlspecialchars($message['sender']); ?></a>] (<?php echo htmlspecialchars($message['date']); ?>): <?php echo htmlspecialchars($message['content']); ?></span>
                <?php if ($message['sender'] === $_SESSION["username"]): ?>
                    <button onclick="deleteMessage(<?php echo $index; ?>)">Delete</button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <textarea autofocus draggable="false" class="messageInput" id="messageInput" placeholder="Type your message here" value=""></textarea>
    <button class="messageSend" onclick="sendMessage('<?php echo htmlspecialchars($user1); ?>', '<?php echo htmlspecialchars($user2); ?>')">Send</button>
</body>
</html>
