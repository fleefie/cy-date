function sendMessage(user1, user2) {
    var messageInput = document.getElementById("messageInput");
    var message = messageInput.value;
    if (message.trim() === "") return;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "script/sendChat.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    messageInput.value = "";
                    loadChat(user1, user2);
                }
            }
        }
    };

    xhr.send("user1=" + encodeURIComponent(user1) + "&user2=" + encodeURIComponent(user2) + "&message=" + encodeURIComponent(message));
}

function loadChat(user1, user2) {
    var chatBox = document.getElementById("chatBox");
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "chat.php?user1=" + encodeURIComponent(user1) + "&user2=" + encodeURIComponent(user2), true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var parser = new DOMParser();
                var doc = parser.parseFromString(xhr.responseText, "text/html");
                var newChatBox = doc.getElementById("chatBox");
                chatBox.innerHTML = newChatBox.innerHTML;
            }
        }
    };

    xhr.send();
}

function deleteMessage(index) {
    console.log("PAS IMPLÉMENTÉ : supprimer le mesasge a index:", index);
}

setInterval(function () {
    const messageInput = document.getElementById("messageInput");
    if (messageInput.value.trim() === "") {
        var user1 = document.getElementById("user1").value;
        var user2 = document.getElementById("user2").value;
        loadChat(user1, user2);
    }
}, 3000);
