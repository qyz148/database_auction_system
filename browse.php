<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php 
 // Include database connection
include 'test_connection.php';
?>

<div class="container">

<h2 class="my-3">Browse listings</h2>

<div id="searchSpecs">
<!-- When this form is submitted, this PHP page is what processes it.
     Search/sort specs are passed to this page through parameters in the URL
     (GET method of passing data to a page). -->
<form method="get" action="browse.php">
  <div class="row">
    <div class="col-md-5 pr-0">
      <div class="form-group">
        <label for="keyword" class="sr-only">Search keyword:</label>
	    <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text bg-transparent pr-0 text-muted">
              <i class="fa fa-search"></i>
            </span>
          </div>
          <input type="text" class="form-control border-left-0" id="keyword" name="keyword" 
                 value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" 
                 placeholder="Search for anything">
        </div>
      </div>
    </div>
    <div class="col-md-3 pr-0">
      <div class="form-group">
        <label for="cat" class="sr-only">Search within:</label>
        <select class="form-control" id="cat" name="cat">
          <option value="all" <?php echo (isset($_GET['cat']) && $_GET['cat'] == 'all') ? 'selected' : ''; ?>>All categories</option>
	  <!-- make the categories option -->
          <?php
          $query_categories = "SELECT CategoryID, ItemCategoryName FROM category";
          $result_categories = mysqli_query($conn, $query_categories);
          while ($row = mysqli_fetch_assoc($result_categories)) {
            $category_id = $row['CategoryID'];
            $category_name = htmlspecialchars($row['ItemCategoryName']);
            $selected = (isset($_GET['cat']) && $_GET['cat'] == $category_id) ? 'selected' : '';
            echo "<option value=\"$category_id\" $selected>$category_name</option>";
          }
          ?>
        </select>
      </div>
    </div>
    <div class="col-md-3 pr-0">
      <div class="form-inline">
        <label class="mx-2" for="order_by">Sort by:</label>
        <select class="form-control" id="order_by" name="order_by">
          <option value="name" <?php echo (isset($_GET['order_by']) && $_GET['order_by'] == 'name') ? 'selected' : ''; ?>>Name</option>
          <option value="price_asc" <?php echo (isset($_GET['order_by']) && $_GET['order_by'] == 'price_asc') ? 'selected' : ''; ?>>Price (low to high)</option>
          <option value="price_desc" <?php echo (isset($_GET['order_by']) && $_GET['order_by'] == 'price_desc') ? 'selected' : ''; ?>>Price (high to low)</option>
        </select>
      </div>
    </div>
    <div class="col-md-1 px-0">
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </div>
</form>
</div> <!-- end search specs bar -->


</div>

<?php
  // Retrieve these from the URL
  if (!isset($_GET['keyword'])) {
     $keyword = ''; 
     error_log("Keyword not set, using default.");// TODO: Define behavior if a keyword has not been specified.
  }
  else {
    $keyword = $_GET['keyword'];
  }
  if (!isset($_GET['cat'])) {
    $category = ''; 
    error_log("Category not set, using default.");// TODO: Define behavior if a category has not been specified.
  }
  else {
    $category = $_GET['cat'];
  }
  
  if (!isset($_GET['order_by'])) {
    $ordering = 'name';  // TODO: Define behavior if an order_by value has not been specified.
  }
  else {
    $ordering = $_GET['order_by'];
  }

  if (!isset($_GET['page'])) {
    $curr_page = 1;
  }
  else {
    $curr_page = $_GET['page'];
  }

  /* TODO: Use above values to construct a query. Use this query to 
     retrieve data from the database. (If there is no form data entered,
     decide on appropriate default value/default query to make. */
  
  /* For the purposes of pagination, it would also be helpful to know the
     total number of results that satisfy the above query */
  
$results_per_page = 2;

  $offset = ($curr_page - 1) * $results_per_page;
  $where_clauses = [];
  if ($keyword != '') {
    $where_clauses[] = "i.ItemName LIKE '%" . mysqli_real_escape_string($conn, $keyword) . "%'";
  }
  if (!empty($category) && $category != 'all') {
    $where_clauses[] = "i.CategoryID = '" . mysqli_real_escape_string($conn, $category) . "'";
  }
  $where_sql = (count($where_clauses) > 0) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

$order_by_sql = '';
  if ($ordering == 'name') {
    $order_by_sql = 'ORDER BY i.ItemName';
  } elseif ($ordering == 'price_asc') {
    $order_by_sql = 'ORDER BY i.CurrentBid ASC';
  } elseif ($ordering == 'price_desc') {
    $order_by_sql = 'ORDER BY i.CurrentBid DESC';
  }

 // caculate the num results for real
  $query_count = "SELECT COUNT(*) AS total FROM item i $where_sql";
  $result_count = mysqli_query($conn, $query_count);
  $row_count = mysqli_fetch_assoc($result_count);
  $num_results = $row_count['total'];

 // Query the items
$query = "SELECT i.ItemID, i.ItemName, i.ItemDescription, i.CurrentBid, i.ClosingDate, i.ItemPicture, c.ItemCategoryName, i.ClosingDate, b.BidAmount 
          FROM item i 
          LEFT JOIN category c ON i.CategoryID = c.CategoryID
	  LEFT JOIN bid b ON i.ItemID = b.ItemID
          $where_sql $order_by_sql 
          LIMIT $offset, $results_per_page";
$result = mysqli_query($conn, $query);
 
$max_page = ceil($num_results / $results_per_page);
?>




<div class="container mt-5">

  <!-- If the result is empty，print an informative message.-->
  <?php if ($num_results == 0): ?>
    <div class="alert alert-info">Sorry, we couldn't find any results for your searching.</div>
  <?php else: ?>
      <ul class="list-group">
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
              <li class="list-group-item">
                  <div class="d-flex align-items-center">
                      <!-- Display the item picture -->
                      <img src="<?php echo htmlspecialchars($row['ItemPicture'] ?? 'images/default.jpg'); ?>" 
                           alt="<?php echo htmlspecialchars($row['ItemName'] ?? 'No Name'); ?>" 
                           style="max-width: 150px; max-height: 150px; margin-right: 15px;">
                      <div>
                          <!-- Display item name as a clickable link -->
                          <h5>
                              <a href="item_details.php?item_id=<?php echo $row['ItemID']; ?>">
                                  <?php echo htmlspecialchars($row['ItemName']); ?>
                              </a>
                          </h5>
                          <p>Category: <?php echo htmlspecialchars($row['ItemCategoryName']); ?></p>
                          <p>Description: <?php echo htmlspecialchars($row['ItemDescription']); ?></p>
                          <p>Current Bid: £<?php echo htmlspecialchars(number_format($row['CurrentBid'], 2)); ?></p>
			  <p>Bid Amount: <?php echo htmlspecialchars(($row['BidAmount'])); ?></p>
			  <?php
                            $current_time = new DateTime();
                            $end_time = new DateTime($row['ClosingDate']);
                            
                            if ($current_time > $end_time): ?>
                                <p class="text-danger">Auction has ended</p>
                            <?php else: ?>
                                <p>Current Bid: £<?php echo htmlspecialchars(number_format($row['CurrentBid'], 2)); ?></p>
                            <?php endif; ?>
                            <p>Auction End Time: <?php echo htmlspecialchars($row['ClosingDate']); ?></p>
                      </div>
                  </div>
              </li>
          <?php endwhile; ?>
      </ul>
  <?php endif; ?>


</div>


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
  
  $high_page_boost = max(3 - $curr_page, 0);
  $low_page_boost = max(2 - ($max_page - $curr_page), 0);
  $low_page = max(1, $curr_page - 2 - $low_page_boost);
  $high_page = min($max_page, $curr_page + 2 + $high_page_boost);
  
  if ($curr_page != 1) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
        <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
        <span class="sr-only">Previous</span>
      </a>
    </li>');
  }
    
  for ($i = $low_page; $i <= $high_page; $i++) {
    if ($i == $curr_page) {
      // Highlight the link
      echo('
    <li class="page-item active">');
    }
    else {
      // Non-highlighted link
      echo('
    <li class="page-item">');
    }
    
    // Do this in any case
    echo('
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
  }
  
  if ($curr_page != $max_page) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </li>');
  }
?>

  </ul>
</nav>


</div>



<?php include_once("footer.php")?>