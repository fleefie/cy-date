<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

function searchUsers($queryLong) {
    $usersDir = "users/";
    $results = [];
    // J'ai pas envie d'expliquer le regex :(
    $queryParts = preg_split('/[ ,.;:]+/', strtolower($queryLong));
 
    if ($handle = opendir($usersDir)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != ".." && is_dir($usersDir . $entry)) {
                $userFile = $usersDir . $entry . "/user.json";
                if (file_exists($userFile)) {
                    $userData = json_decode(file_get_contents($userFile), true);
                    $ageRange = explode("-", $userData["age"]);

                    // Note a ceux qui lisent le code : on utilise une logique
                    // exclusive prcq sinon bah on peut pas gérer plusieurs
                    // critères a la fois.
                    $matches = true;
                    foreach ($queryParts as $query) {
                        $query = trim($query);
                        if (stripos($entry, $query) === false && 
                            strtolower($userData["gender"]) !== $query && 
                            stripos($userData["tags"], $query) === false && 
                            !($ageRange[0] <= $query && $query <= $ageRange[1]) && 
                            strtolower($userData["region"]) !== $query) {
                            $matches = false;
                            break;
                        }
                    }

                    if ($matches) {
                        $results[$entry] = $userData;
                    }
                }
            }
        }
        closedir($handle);
    }
    return $results;
}

function shortDescription($description, $length) {
    if (strlen($description) > $length) {
        return substr($description, 0, $length) . '...';
    }
    return $description;
}

$query = isset($_GET["query"]) ? $_GET["query"] : "";
$searchResults = searchUsers($query);
?>

<!DOCTYPE html>
<head>
    <title>Search Results</title>
    <link rel="stylesheet" href="assets/main.css">
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
    <form class="recherche" method="GET" action="search.php">
        <input type="text" name="query" placeholder="Search for tags or users" value="<?php echo htmlspecialchars($query); ?>">
    </form>
    <div class="box">
    <ul>
        <?php
        if (!empty($searchResults)) {
            foreach ($searchResults as $username => $userData) {
                echo "<li><strong><a href=viewUser.php?user=" . htmlspecialchars($username) . ">" . htmlspecialchars($username) . "</a></strong><br>";
                echo "Gender: " . htmlspecialchars($userData["gender"]) . "<br>";
                echo "Age: " . htmlspecialchars($userData["age"]) . "<br>";
                echo "Region: " . htmlspecialchars($userData["region"]) . "<br>";
                echo "Tags: " . htmlspecialchars($userData["tags"]) . "<br>";
                echo "Description: " . htmlspecialchars(shortDescription($userData["description"], 100)) . "</li><br>";
            }
        } else {
            echo "<li>No results found.</li>";
        }
        ?>
    </ul>
    </div>
</body>
</html>