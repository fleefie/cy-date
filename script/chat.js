function sendMessage(user1, user2) {
    const messageInput = document.getElementById("messageInput");
    const message = messageInput.value;
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

let xhr = null;

function loadChat(user1, user2) {
    if (xhr) {
        xhr.abort();
    }
    const chatBox = document.getElementById("chatBox");
    xhr = new XMLHttpRequest();
    xhr.open("GET", "chat.php?user1=" + encodeURIComponent(user1) + "&user2=" + encodeURIComponent(user2), true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(xhr.responseText, "text/html");
                const newChatBox = doc.getElementById("chatBox");

                chatBox.innerHTML = newChatBox.innerHTML;
                chatBox.scrollTop = chatBox.scrollHeight;
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
        const user1 = document.getElementById("user1").value;
        const user2 = document.getElementById("user2").value;
        loadChat(user1, user2);
    }
}, 3000);
