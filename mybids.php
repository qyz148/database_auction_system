<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">My bids</h2>

<?php
  // This page is for showing a user the auctions they've bid on.
  // It will be pretty similar to browse.php, except there is no search bar.
  // This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.
  
  
  // TODO: Check user's credentials (cookie/session).
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
  }
  
  // TODO: Perform a query to pull up the auctions they've bidded on.
  require('database.php');
  $user_id = $_SESSION['user_id'];
  $query = "SELECT * FROM bids WHERE user_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('i', $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  
  // TODO: Loop through results and print them out as list items.
  while ($row = $result->fetch_assoc()) {
    echo "<li>Auction ID: " . $row['auction_id'] . " - Bid Amount: " . $row['bid_amount'] . "</li>";
  }
  
?>

<?php include_once("footer.php")?>