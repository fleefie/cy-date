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
    <style>
        .nav li {
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="nav">
        <ul>
            <li><a href="main.php">Home</a>
            <li><a href="followList.php">Followed</a>
            <li><a href="viewUser.php?<?php echo 'user='.$_SESSION['username'];?>">My Page</a>
            <li><a href="userConfig.php">Settings</a>
            <li><a href="logoff.php">Log Out</a> 
        </ul>
    </div>
    <form method="GET" action="search.php">
        <input type="text" name="query" placeholder="Search..." value="<?php echo htmlspecialchars($query); ?>">
        <button type="submit">Search</button>
    </form>

    <h1>Search Results</h1>
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
</body>
</html>