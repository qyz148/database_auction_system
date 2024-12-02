<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php require("test_connection.php")?>

<div class="container">

<h2 class="my-3">Recommendations for you</h2>

<?php
  // This page is for showing a buyer recommended items based on their bid 
  // history. It will be pretty similar to browse.php, except there is no 
  // search bar. This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.
  
  
  // TODO: Check user's credentials (cookie/session).
  $userID = $_SESSION['user_id'];

  // TODO: Perform a query to pull up auctions they might be interested in.
  $sql = "
   select i.ItemID as ItemID, c.ItemCategoryName as CategoryName, i.ItemName as ItemName, i.ItemDescription as ItemDescription, i.ClosingDate as ClosingDate from Item i join category c on i.CategoryID = c.CategoryID where ItemID in (select DISTINCT(ItemID) from bid where UserID in (select DISTINCT(UserID) from bid where ItemID in (select ItemID as originalItem from bid where UserID = ?) and UserID != ?)) AND ItemID NOT in (select ItemID from bid where UserID = ?);
  ";
  $stmt = $conn->prepare($sql);
  $stmt-> bind_param("sss", $userID, $userID, $userID);
  $stmt-> execute();
  $result = $stmt->get_result();
  echo "<div>";
  if($result->num_rows>0){
    $tableHeader = '<div>
    <style>
      .tb { border-collapse: collapse; width:300px; }
      .tb th, .tb td { padding: 5px; border: solid 1px #777; }
      .tb th { background-color: lightblue; }
    </style>
    
      <table style="width:1200px;padding-bottom: 20px;" class="tb">
        <tr>
          <th id="Bid_ID" style="width:50px">Name</th>
          <th id="Item_Name" style="width:100px">Category</th>
          <th id="Bid_Amount" style="width:100px">Description</th>
          <th id="Bid_Time" style="width:100px">Closing Date</th>
        </tr>
      ';


    echo $tableHeader;
    while ($row = $result->fetch_assoc()) {
      echo "
          <tr>
            <td style='width:50px'><a href='listing.php?item_id=" . $row['ItemID'] . "'>" . $row['ItemName'] . "</a></td>
            <td style='width:100px'>" . $row['CategoryName'] . "</td>
            <td style='width:100px'>" . $row['ItemDescription'] . "</td>
            <td style='width:100px'>" . $row['ClosingDate'] . " (UTC)</td>
          </tr>       
      ";
    }
    echo "</tbale></div>";
  }else{
    echo "<p>Please make a bid to kick start the recommendation.</p>";
  }
  // TODO: Loop through results and print them out as list items.
  echo '</div>';
?>