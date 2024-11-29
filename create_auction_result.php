<?php include("header.php"); ?>
<?php include_once("test_connection.php"); ?>

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

// 插入到数据库的逻辑
$stmt = $conn->prepare(
    "INSERT INTO Auction (UserID, DateOfPurchase, ReservePrice, PurchasePrice, AuctionStatus, AuctionStartingTime) 
     VALUES (?, NULL, ?, ?, ?, ?)"
);

// 设置绑定变量的值
$UserID = $_SESSION['user_id']; // 从会话中获取用户 ID
$auctionStatus = 'Active'; // 默认拍卖状态为 "Active"
$startingTime = date('Y-m-d H:i:s'); // 当前时间

// 绑定参数
$stmt->bind_param(
    "iddss",
    $UserID,
    $auctionReservePrice,
    $auctionStartPrice,
    $auctionStatus,
    $startingTime
);

if ($stmt->execute()) {
    $newAuctionID = $stmt->insert_id; 
    echo('<div class="text-center alert alert-success">Auction successfully created! View your new listing.</div>');
} else {
    echo('<div class="alert alert-danger">Failed to create auction: ' . htmlspecialchars($stmt->error) . '</div>');
}

$stmt->close();
$conn->close();

echo('<div class="text-center">Auction successfully created! <a href="mylistings.php">View your new listing.</a></div>');


?>
</div>


<?php include_once("footer.php")?>