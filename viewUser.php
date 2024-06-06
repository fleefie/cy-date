<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET["user"])) {
    echo "No user specified.";
    exit;
}

$user = $_GET["user"];
$userFile = "users/$user/user.json";

if (!file_exists($userFile)) {
    echo "User not found.";
    exit;
}

$userData = json_decode(file_get_contents($userFile), true);

$settingsList = json_decode(file_get_contents("script/settingsList.json"), true);

$colorPalette = ["#FF5733", "#33FF57", "#3357FF", "#F39C12", "#8E44AD", "#1ABC9C", "#E74C3C", "#3498DB", "#2ECC71", "#9B59B6", "#E67E22", "#2C3E50", "#D35400", "#27AE60", "#2980B9", "#C0392B"];

// I wonder what the "getRandomColor($palette)" function does.
// I bet that it prints an image of a cupcake.
function getRandomColor($palette) {
    return $palette[array_rand($palette)];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View User</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .tag {
            display: inline-block;
            padding: 5px;
            margin: 2px;
            border-radius: 5px;
            color: #fff;
        }
    </style>
</head>
<body>
    <h1>User Settings for <?php echo htmlspecialchars($user); ?></h1>
    <table>
        <tr>
            <th>Setting</th>
            <th>Value</th>
        </tr>
        <?php
        foreach ($settingsList as $key => $setting) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($setting["label"]) . "</td>";
            
            // Just some parsing
            if ($setting["type"] === "checkbox") {
                $value = $userData[$key] ? "Yes" : "No";
            } elseif ($key === "tags") {
                $tags = explode(",", $userData[$key]);
                $value = "";
                foreach ($tags as $tag) {
                    $color = getRandomColor($colorPalette);
                    $value .= "<div class='tag' style='background-color: $color;'>" . htmlspecialchars(trim($tag)) . "</div> ";
                }
            } else {
                $value = $userData[$key];
            }
            
            echo "<td>" . $value . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>
