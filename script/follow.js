function toggleState(btn, target) {
    
    var type;
    switch(btn.innerText) {
        case "Follow":
            type = "follow";
            break;
        case "Unfollow":
            type = "unfollow"; 
            break;
        case "Block":
            type = "block";
            break;
        case "Unblock":
            type = "unblock"
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "script/follow.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    switch (btn.innerText) {
                        case "Follow":
                            btn.innerText = "Unfollow";
                            break;
                        case "Unfollow":
                            btn.innerText = "Follow";
                            break;
                        case "Block":
                            btn.innerText = "Unblock";
                            document.getElementById("follow").disabled = true;
                            document.getElementById("follow").innerText = "Follow";
                            break;
                        case "Unblock":
                            btn.innerText = "Block";
                            document.getElementById("follow").disabled = false;
                            break;
                    }
                } else {
                    btn.innerText = "Error";
                }
            }
        }
    };

    var params = "target=" + target + "&type=" + type;
    xhr.send(params);
}