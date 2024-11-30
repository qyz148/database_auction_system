<?php include_once("header.php") ?>
<?php require("utilities.php") ?>
<?php include("test_connection.php") ?>

<?php
  // Get info from the URL:
  $item_id = $_GET['item_id'];
  // TODO: Use item_id to make a query to the database.
  $conn = new mysqli("localhost", "root", "", "auction_system");
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Fetch item details from the database
  $sql = "SELECT ItemName AS title, ItemDescription AS description, CurrentBid AS current_price, ClosingDate AS end_time 
          FROM item 
          WHERE ItemID = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $item_id);
  $stmt->execute();
  $stmt->bind_result($title, $description, $current_price, $end_time);
  $stmt->fetch();
  $stmt->close();
  $conn->close();

  $end_time = new DateTime($end_time);

  // TODO: Note: Auctions that have ended may pull a different set of data,
  //       like whether the auction ended in a sale or was cancelled due
  //       to lack of high-enough bids. Or maybe not.
  
  // Calculate time to auction end:
  $now = new DateTime();
  if ($now < $end_time) {
    $time_to_end = date_diff($now, $end_time);
    $time_remaining = ' (in ' . display_time_remaining($time_to_end) . ')';
  }

  // TODO: If the user has a session, use it to make a query to the database
  //       to determine if the user is already watching this item.
  //       For now, this is hardcoded.
  $has_session = true; // Simulate session for development
  $watching = false;   // Simulate watchlist status for development
?>

<div class="container">

<div class="row"> <!-- Row #1 with auction title + watch button -->
  <div class="col-sm-8"> <!-- Left col -->
    <h2 class="my-3"><?php echo htmlspecialchars($title); ?></h2>
  </div>
</div>

<div class="row"> <!-- Row #2 with auction description + bidding info -->
  <div class="col-sm-8"> <!-- Left col with item info -->
    <div class="itemDescription">
      <?php echo nl2br(htmlspecialchars($description)); ?>
    </div>
  </div>

  <div class="col-sm-4"> <!-- Right col with bidding info -->
    <p>
      <?php if ($now > $end_time): ?>
        This auction ended <?php echo(date_format($end_time, 'j M H:i')); ?>
        <!-- TODO: Print the result of the auction here? -->
      <?php else: ?>
        Auction ends <?php echo(date_format($end_time, 'j M H:i') . $time_remaining); ?></p>
        <p class="lead">Current bid: £<?php echo(number_format($current_price, 2)); ?></p>

        <!-- Bidding form -->
        <form method="POST" action="place_bid.php">
          <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
          <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>"> <!-- Replace with the actual logged-in user's ID -->
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">£</span>
            </div>
            <input type="number" name="bid_amount" class="form-control" step="0.01" min="0" required>
          </div>
          <button type="submit" class="btn btn-primary form-control">Place bid</button>
        </form>
      <?php endif; ?>
  </div> <!-- End of right col with bidding info -->
</div> <!-- End of row #2 -->

</div> <!-- End of container -->

<?php include_once("footer.php") ?>

<script> 
// JavaScript functions: addToWatchlist and removeFromWatchlist.

function addToWatchlist(button) {
  console.log("These print statements are helpful for debugging btw");

  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'add_to_watchlist', arguments: [<?php echo($item_id);?>]},

    success: 
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();
 
        if (objT == "success") {
          $("#watch_nowatch").hide();
          $("#watch_watching").show();
        }
        else {
          var mydiv = document.getElementById("watch_nowatch");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Add to watch failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); // End of AJAX call

} // End of addToWatchlist func

function removeFromWatchlist(button) {
  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'remove_from_watchlist', arguments: [<?php echo($item_id);?>]},

    success: 
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();
 
        if (objT == "success") {
          $("#watch_watching").hide();
          $("#watch_nowatch").show();
        }
        else {
          var mydiv = document.getElementById("watch_watching");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Watch removal failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); // End of AJAX call

} // End of addToWatchlist func
</script>
