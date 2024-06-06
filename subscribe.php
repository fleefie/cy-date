<?php 
session_start();

if(!isset($_SESSION['username'])){
  header("Location: login.php");
  exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  $subscription = [
    ["name" => "Monthly", "price" =>: "10", "duration" = "1 month"],
    ["name" => "Quarterly", "price" =>: "25", "duration" = "3 months"],
    ["name" => "Annually", "price" => : "50", "duration" = "1 year"],
    ["name" => "Trial", "price" => "Free" : "2", "duration" = "48 hours"],    
  ]


$isFemale = $_SESSION['gender'] === "female";

$SelectedSubscriptionIndex = intval($_POST['subscription']);
$subscription = $subscriptions[$SelectedSubscriptionIndex];

if ($isFemale){
  $subscription['price'] = 0;
}

$CUcoins = $_SESSION['coins'];
if ($CUcoins < $subscription['price']){
  echo "You don't have enough coins to subscribe";
  exit;
}

$_SESSION['coins'] = $CUcoins - $subscription['price'];

$_SESSION['is_subscribed'] = true;
$_SESSION['subscription'] = $subscription;
$userFile = "user/{$_SESSION['username']}/user_data.json";
if(file_exists($userFile)){
  $userData = json_decode(file_get_contents($userFile), true); 
} else {
  $userData = [];
}

$userData['is_subscribed'] = true;
$userData['subscription'] = $subscription;
file_put_contents($userFile, json_encode($userData, JSON_PRETTY_PRINT));
header("Location: index.php");
  exit;
}
?>
