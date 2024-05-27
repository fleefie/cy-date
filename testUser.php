<!DOCTYPE html>
<html>
<head>
    <title>Test</title>
    <script src="script/follow.js"></script>
</head>
<body>
    <h1>User Profile</h1>
    <p id="followStatus">Not Following</p>
    <p id="blockStatus">Not Blocked</p>
    <button id="followBtn" onclick="followUser('testUserPleaseIgnore', 'follow')">Follow</button>
    <button id="unfollowBtn" onclick="followUser('testUserPleaseIgnore', 'unfollow')">Unfollow</button>
    <button id="blockBtn" onclick="followUser('testUserPleaseIgnore', 'block')">Block</button>
    <button id="unblockBtn" onclick="followUser('testUserPleaseIgnore', 'unblock')">Unblock</button>
</body>
</html>