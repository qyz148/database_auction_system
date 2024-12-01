<?php include("header.php")?>
<?php require("utilities.php")?>
<?php require("test_connection.php")?>


<div class="container">

<h2 class="my-3">My bids</h2>

<?php
  // This page is for showing a user the auctions they've bid on.
  // It will be pretty similar to browse.php, except there is no search bar.
  // This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.
  
  
  // TODO: Check user's credentials (cookie/session).
  if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
  }
  
  // TODO: Perform a query to pull up the auctions they've bidded on.
  $user_id = $_SESSION['user_id'];
  $query = "SELECT b.BidID as BidID, b.BidAmount as BidAmount, i.ItemName as ItemName, b.TimeOfBid as BidTime, i.ItemID as ItemID, b.TimeOfBid as TimeOfBid FROM bid as b LEFT JOIN item as i on b.ItemID = i.ItemID where b.UserID = ? ";
  $direction = "";
  $sortByy ="";
  if(isset($_GET['sortBy']) && isset($_GET['dir'])){
    $direction=$_GET['dir'];
    $sortByy = $_GET['sortBy'];
    $query = $query . "order by " . str_replace("_", "", $_GET['sortBy']) . " " .$_GET['dir'];
  }
  
  $stmt = $conn->prepare($query);
  $stmt->bind_param('i', $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  if($result->num_rows >0){
    echo ('
    <script>
      function sortTable(item){
        console.log(item)
        var name = item
        var header = document.getElementById(name)
        var dir = header.innerHTML.slice(-1)
        console.log(dir)
        if(dir == "+"){
          // header.innerHTML = name.replace("_", " ") + " -"
          window.location = window.location.origin + "/mybids.php?sortBy="+name+"&dir=DESC"
        }else if(dir == "-"){
          // header.innerHTML = name.replace("_", " ")
          window.location =window.location.origin + "/mybids.php"
        }else{
          // header.innerHTML = name.replace("_", " ") + " +"
          window.location = window.location.origin + "/mybids.php?sortBy="+name+"&dir=ASC"
        }
      }
      function getTitle(sortBy, dire, curSort){
        var name = sortBy
        var header = document.getElementById(sortBy)
        var dir = dire
        console.log(dir)
        if(name == curSort){
          if(dir == "ASC"){
            header.innerHTML = name.replace("_", " ") + " +"
          }else if(dir == "DESC"){
            header.innerHTML = name.replace("_", " ") + " -"
          }else{
            header.innerHTML = name.replace("_", " ") + ""
          }
        }else{
          header.innerHTML = name.replace("_", " ") + ""
        }

      }
    </script>
  ');

    $tableHeader = '<table style="width:50vw">
      <tr>
        <th id="Bid_ID" style="width:50px" onclick="sortTable(`Bid_ID`)"></th>
        <th id="Item_Name" style="width:50px" onclick="sortTable(`Item_Name`)"></th>
        <th id="Bid_Amount" style="width:50px" onclick="sortTable(`Bid_Amount`)"></th>
        <th id="Bid_Time" style="width:50px" onclick="sortTable(`Bid_Time`)"></th>
      </tr>
    ';
  }

  echo $tableHeader;
  while ($row = $result->fetch_assoc()) {
    echo "
        <tr>
          <td style='width:50px'>" . $row['BidID'] . "</td>
          <td style='width:50px'><a href='listing.php?item_id=" . $row['ItemID'] . "'>" . $row['ItemName'] . "</a></td>
          <td style='width:50px'>" . $row['BidAmount'] . "</td>
          <td style='width:50px'>" . $row['TimeOfBid'] . "</td>
        </tr>       
    ";
  }
  echo "</tbale>";
  echo '
    <script>
      getTitle(`Bid_ID`,"'.$direction.'","'.$sortByy.'")
      getTitle(`Item_Name`,"'.$direction.'","'.$sortByy.'")
      getTitle(`Bid_Amount`,"'.$direction.'","'.$sortByy.'")
      getTitle(`Bid_Time`,"'.$direction.'","'.$sortByy.'")
    </script>
  ';

?>

<?php include_once("footer.php")?>