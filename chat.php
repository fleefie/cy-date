<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

$user1 = $_GET['user1'];
$user2 = $_GET['user2'];

if ($_SESSION["username"] !== $user1 && $_SESSION["username"] !== $user2) {
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
</head>
<body>
    <input type="hidden" id="user1" value="<?php echo htmlspecialchars($user1); ?>">
    <input type="hidden" id="user2" value="<?php echo htmlspecialchars($user2); ?>">
    <div id="chatBox">
        <?php foreach ($chatData as $index => $message): ?>
            <div>
                <span>[<?php echo htmlspecialchars($message['sender']); ?>] (<?php echo htmlspecialchars($message['date']); ?>): <?php echo htmlspecialchars($message['content']); ?></span>
                <?php if ($message['sender'] === $_SESSION["username"]): ?>
                    <button onclick="deleteMessage(<?php echo $index; ?>)">Delete</button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <input type="text" id="messageInput" placeholder="Type your message here" value="">
    <button onclick="sendMessage('<?php echo htmlspecialchars($user1); ?>', '<?php echo htmlspecialchars($user2); ?>')">Send</button>
</body>
</html>
