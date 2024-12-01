<?php include_once("header.php") ?>
<?php require("utilities.php") ?>
<?php include("test_connection.php") ?>
<?php include("notification_nav.php"); ?>

<div class="container">

<h2 class="my-3">My Listings</h2>

<?php
  // This page is for showing a user the auction listings they've made.
  // It will be pretty similar to browse.php, except there is no search bar.
  // This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.
  
  // TODO: Check user's credentials (cookie/session).
  
  if (!isset($_SESSION['user_id'])) {
      echo "<p>Please <a href='login.php'>log in</a> to view your listings.</p>";
      exit();
  }

  $user_id = $_SESSION['user_id']; // Get the logged-in user's ID.

  // Pagination setup.
  $results_per_page = 10; // Number of results per page.
  $curr_page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page.
  $offset = ($curr_page - 1) * $results_per_page;

  // TODO: Perform a query to pull up their auctions.
  $conn = new mysqli("localhost", "root", "", "auction_system");
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  // Count total number of listings for pagination.
  $count_sql = "SELECT COUNT(*) FROM item WHERE UserID = ?";
  $count_stmt = $conn->prepare($count_sql);
  $count_stmt->bind_param("s", $user_id);
  $count_stmt->execute();
  $count_stmt->bind_result($total_results);
  $count_stmt->fetch();
  $count_stmt->close();

  $max_page = ceil($total_results / $results_per_page);

  // Query to get the user's listings.
  $sql = "SELECT ItemID, ItemName, ItemDescription, CurrentBid, ClosingDate, ItemPicture, 
                 (SELECT COUNT(*) FROM bid WHERE bid.ItemID = item.ItemID) AS num_bids 
          FROM item 
          WHERE UserID = ?
          ORDER BY ClosingDate ASC 
          LIMIT ?, ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sii", $user_id, $offset, $results_per_page);
  $stmt->execute();
  $stmt->bind_result($item_id, $item_name, $item_description, $current_bid, $closing_date, $item_picture, $num_bids);

  // TODO: Loop through results and print them out as list items.
  if ($total_results == 0) {
      echo "<p>You have no listings.</p>";
  } else {
      echo '<ul class="list-group">';
      while ($stmt->fetch()) {
          // Calculate time to auction end.
          $end_date = new DateTime($closing_date);
          print_listing_li($item_id, $item_name, $item_description, $current_bid, $num_bids, $end_date, $item_picture);
      }
      echo '</ul>';
  }

  $stmt->close();
  $conn->close();
?>

<!-- Pagination for results listings -->
<nav aria-label="Search results pages" class="mt-5">
  <ul class="pagination justify-content-center">

<?php
  // Copy any currently-set GET variables to the URL.
  $querystring = "";
  foreach ($_GET as $key => $value) {
      if ($key != "page") {
          $querystring .= "$key=$value&amp;";
      }
  }

  // Pagination logic (similar to browse.php).
  $high_page_boost = max(3 - $curr_page, 0);
  $low_page_boost = max(2 - ($max_page - $curr_page), 0);
  $low_page = max(1, $curr_page - 2 - $low_page_boost);
  $high_page = min($max_page, $curr_page + 2 + $high_page_boost);

  if ($curr_page != 1) {
      echo('
      <li class="page-item">
        <a class="page-link" href="mylistings.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
          <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
          <span class="sr-only">Previous</span>
        </a>
      </li>');
  }

  for ($i = $low_page; $i <= $high_page; $i++) {
      if ($i == $curr_page) {
          echo('<li class="page-item active">');
      } else {
          echo('<li class="page-item">');
      }

      echo('<a class="page-link" href="mylistings.php?' . $querystring . 'page=' . $i . '">' . $i . '</a></li>');
  }

  if ($curr_page != $max_page) {
      echo('
      <li class="page-item">
        <a class="page-link" href="mylistings.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
          <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
          <span class="sr-only">Next</span>
        </a>
      </li>');
  }
?>
  </ul>
</nav>

</div>

<?php include_once("footer.php") ?>
