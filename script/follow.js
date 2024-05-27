function followUser(target) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "script/follow.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    document.getElementById("followStatus").innerText = "Following";
                } else {
                    document.getElementById("followStatus").innerText = response.message;
                }
            } else {
                console.error("Error: " + xhr.statusText);
            }
        }
    };

    var params = "target=" + encodeURIComponent(target);
    xhr.send(params);
}

function unfollowUser(target) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "script/unfollow.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    document.getElementById("followStatus").innerText = "Not Following";
                } else {
                    document.getElementById("followStatus").innerText = response.message;
                }
            } else {
                console.error("Error: " + xhr.statusText);
            }
        }
    };

    var params = "target=" + encodeURIComponent(target);
    xhr.send(params);
}