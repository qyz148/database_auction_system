<?php
include("test_connection.php");
include("utilities.php");
session_start();
date_default_timezone_set('UTC');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to view your notifications.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Function to add a notification to the inbox table
function addInboxMessage($conn, $user_id, $message_content, $message_type) {
    $inboxID = uuid4();
    $sql = "INSERT INTO inbox (InboxID, UserID, MessageContent, MessageType) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssss", $inboxID, $user_id, $message_content, $message_type);
    if ($stmt->execute()) {
        echo "Notification added for UserID: $user_id\n";
    } else {
        echo "Failed to add notification for UserID: $user_id. Error: " . $stmt->error;
    }

    $stmt->close();
}

// Check for auctions that ended recently and process notifications
if (isset($_GET['check_auctions'])) {
    $now = date('Y-m-d H:i:s');
    // Step 1: Fetch auctions that have ended but not processed
    $sql = "SELECT a.AuctionID, a.ItemID, a.UserID AS SellerID 
            FROM auction a
            WHERE a.AuctionStatus = 'Active' AND date(a.AuctionStartingTime) <= date(NOW())";
    $stmt = $conn->prepare($sql);
    // $stmt->bind_param("s", $now);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "No ended auctions found.";
        exit;
    }

    while ($row = $result->fetch_assoc()) {
        $auction_id = $row['AuctionID'];
        echo $auction_id;

        $item_id = $row['ItemID'];
        $seller_id = $row['SellerID'];

        // Step 2: Find the highest bid for the auction from `bid` table
        $bid_sql = "SELECT UserID AS BuyerID, BidAmount 
                    FROM bid 
                    WHERE ItemID = ? 
                    ORDER BY BidAmount DESC LIMIT 1";
        $bid_stmt = $conn->prepare($bid_sql);
        $bid_stmt->bind_param("s", $item_id);
        $bid_stmt->execute();
        $bid_result = $bid_stmt->get_result();

        if ($bid_result->num_rows === 0) {
            echo "No bids found for ItemID: $item_id.";
            continue; // Skip this auction if no bids found
        }

        $highest_bid = $bid_result->fetch_assoc();
        $buyer_id = $highest_bid['BuyerID'];
        $final_price = $highest_bid['BidAmount'];
        $bid_stmt->close();

        // Step 3: Update auction status and final price
        $update_sql = "UPDATE auction SET AuctionStatus = 'Completed', PurchasePrice = ? WHERE AuctionID = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ds", $final_price, $auction_id);

        if ($update_stmt->execute()) {
            echo "Auction updated successfully for AuctionID: $auction_id.\n";
        } else {
            echo "Failed to update auction for AuctionID: $auction_id.\n";
        }

        $update_stmt->close();

        // Step 4: Add notifications to inbox
        addInboxMessage($conn, $buyer_id, "Congratulations! You won the auction for item $item_id at £$final_price.", "Auction Win");
        addInboxMessage($conn, $seller_id, "Your auction for item $item_id has ended with a final price of £$final_price.", "Auction End");
    }

    $stmt->close();
    echo json_encode(["status" => "checked"]);
    exit;
}

// Retrieve notifications for the current user
$sql = "SELECT InboxID, MessageContent, MessageType FROM inbox WHERE UserID = ? ORDER BY InboxID DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Notifications</h2>

    <!-- Notification List -->
    <?php if (!empty($notifications)): ?>
        <ul class="list-group" id="notification-list">
            <?php foreach ($notifications as $notification): ?>
                <li class="list-group-item">
                    <p>
                        <strong><?php echo htmlspecialchars($notification['MessageType']); ?>:</strong>
                        <?php echo htmlspecialchars($notification['MessageContent']); ?>
                    </p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No notifications available.</p>
    <?php endif; ?>
</div>

<script>
// Check for ended auctions every 10 seconds
setInterval(function() {
    $.ajax({
        url: "inbox.php?check_auctions=true",
        type: "GET",
        success: function(data) {
            console.log("Auction check complete:", data);
        }
    });
}, 10000);
</script>
</body>
</html>
