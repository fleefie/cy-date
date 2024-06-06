<?php
session_start();
if (!isset($_SESSION['username'])){
  header("Location: login.php");
  exit;
}


$isfemale = $_SESSION['gender'] === "female";

$isSubscribed = isset($_GET['is_subscribed']) ? $_GET['is_subscribed'] : false;

if($isSubscribed){
  echo "You are already subscribed";
  exit;
}

$subscriptions = [
  ["name" => "Monthly", "price" => $isFemale ? "Free" : "10", "duration" = "1 month"],
  ["name" => "Quarterly", "price" => $isFemale ? "Free" : "25", "duration" = "3 months"],
  ["name" => "Annually", "price" => $isFemale ? "Free" : "50", "duration" = "1 year"],
  ["name" => "Trial", "price" => $isFemale ? "Free" : "2", "duration" = "48 hours"],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content=width=device-width, initial-scale=1.0">
  <title>Subscriptions Offers</title>
</head>
<body>
  <h1>Choose one of those Offers</h1>
  <form action="subscribe.php" method="post">
    <?php foreac($subscriptions as $subscription): ?>
    <div>
      <input type="radio" name="subscription" id="<?php echo $subscription['name']; ?>" value="<?php echo $subscription['name']; ?>" required>
  <label for="<?php echo $subscription['name']; ?>">
    <?php echo $subscription['name']; ?> - <?php echo $subscription['price']; ?> - <?php echo $subscription['duration']; ?>
  </label>
    </div>
  <?php endforeach; ?>
        <inpu type="submit" value="Subscribe">
        </form>
</body>
</html>
      
  
