function followUser(target, type) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "script/follow.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    switch(type) {
                        case "follow":
                            document.getElementById("followStatus").innerText = "Followed";
                            break;
                        case "block":
                            document.getElementById("blockStatus").innerText = "Blocked";
                            break;
                        case "unfollow":
                            document.getElementById("followStatus").innerText = "Unfollowed";
                            break;
                        case "unblock":
                            document.getElementById("blockStatus").innerText = "Unblocked";
                            break;
                        default:
                            break;
                    }
                } else {
                    switch(type) {
                        case "follow":
                        case "unfollow":
                            document.getElementById("followStatus").innerText = response.message;
                            break;
                        case "block":
                        case "unblock":
                            document.getElementById("blockStatus").innerText = response.message;
                            break;
                        default:
                            break;
                    }
                }
            } else {
                console.error("Error: " + xhr.statusText);
            }
        }
    };

    var params = "target=" + target + "&type=" + type;
    xhr.send(params);
}