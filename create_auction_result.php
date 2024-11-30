<?php include("header.php"); ?>
<?php include_once("test_connection.php"); ?>
<?php include("utilities.php"); ?>

<div class="container my-5">

<?php

$auctionTitle = isset($_POST['auctionTitle']) ? trim($_POST['auctionTitle']) : '';
$auctionDetails = isset($_POST['auctionDetails']) ? trim($_POST['auctionDetails']) : '';
$auctionCategory = isset($_POST['auctionCategory']) ? trim($_POST['auctionCategory']) : 0;
$auctionStartPrice = isset($_POST['auctionStartPrice']) ? floatval($_POST['auctionStartPrice']) : 0.0;
$auctionReservePrice = isset($_POST['auctionReservePrice']) && $_POST['auctionReservePrice'] !== '' 
    ? floatval($_POST['auctionReservePrice']) 
    : null;

$auctionEndDate = isset($_POST['auctionEndDate']) ? trim($_POST['auctionEndDate']) : '';

$errors = [];
if (empty($auctionTitle)) $errors[] = 'The title of the auction is required.';
if (empty($auctionDetails)) $errors[] = 'Details about the auction are required.';
if (empty($auctionCategory)) $errors[] = 'A valid category must be selected.';
if ($auctionStartPrice <= 0) $errors[] = 'Starting price must be greater than 0.';
// set timezone to UTC before using time() function
date_default_timezone_set('UTC');
if (empty($auctionEndDate) || strtotime($auctionEndDate) <= time()) {
    $errors[] = 'End date must be valid and in the future.';
}

if (!empty($errors)) {
    echo '<div class="alert alert-danger"><ul>';
    foreach ($errors as $error) echo '<li>' . htmlspecialchars($error) . '</li>';
    echo '</ul></div>';
    include_once("footer.php");
    exit();
}


$UserID = $_SESSION['user_id']; 
$auctionStatus = 'Active'; 
$startingTime = date('Y-m-d H:i:s'); 
$_null = null;

// select catergoryID
$stmt = 
    "SELECT CategoryID FROM Category WHERE ItemCategoryName = '" . $auctionCategory . "';";

$query_result = $conn->query($stmt);
$categoryID = "";
while($row = $query_result->fetch_assoc()){
    $categoryID = $row["CategoryID"];
}

// insert into item
$stmt = $conn->prepare(
    "INSERT INTO Item (ItemID, UserID, CategoryID, ItemName, ItemDescription, StartingPrice, ClosingDate, CurrentBid) VALUES (?,?,?,?,?,?,?,?);"
);
$itemID = uuid4();
$stmt->bind_param(
    "ssssssss",
    $itemID,
    $UserID,
    $categoryID,
    $auctionTitle,
    $auctionDetails,
    $auctionStartPrice,
    $auctionEndDate,
    $auctionStartPrice
);
$stmt->execute();

$auctionID = uuid4();
$stmt = $conn->prepare(
    "INSERT INTO Auction (AuctionID, UserID, itemID, DateOfPurchase, ReservePrice, PurchasePrice, AuctionStatus, AuctionStartingTime) 
     VALUES (?, ?, ?, ?, ?, ?, ?, ?);"
);

$stmt->bind_param(
    "ssssssss",
    $auctionID,
    $UserID,
    $itemID,
    $_null,
    $auctionReservePrice,
    $auctionStartPrice,
    $auctionStatus,
    $startingTime
);

if ($stmt->execute()) {
    $newAuctionID = $stmt->insert_id; 
    echo('<div class="text-center">Auction successfully created! <a href="mylistings.php">View your new listing.</a></div>');
} else {
    echo('<div class="alert alert-danger">Failed to create auction: ' . htmlspecialchars($stmt->error) . '</div>');
}

$stmt->close();
$conn->close();




?>
</div>


<?php include_once("footer.php")?>