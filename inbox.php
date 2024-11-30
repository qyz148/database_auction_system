<?php
include("test_connection.php");
include("utilities.php");

// Set script to check every 10 seconds
set_time_limit(0); // Ensure the script can run continuously (not limited by timeout).

while (true) {
    $now = new DateTime();

    // Step 1: Select auctions that have closed
    $conn = new mysqli("localhost", "root", "", "auction_system");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT a.AuctionID, a.ItemID, a.UserID AS SellerID, i.ItemName, a.ClosingDate 
            FROM auction a
            JOIN item i ON a.ItemID = i.ItemID
            WHERE a.ClosingDate < NOW() AND a.AuctionStatus = 'active'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $auction_id = $row['AuctionID'];
            $item_id = $row['ItemID'];
            $seller_id = $row['SellerID'];
            $item_name = $row['ItemName'];

            // Step 2: Find the highest bid for the auction
            $bid_sql = "SELECT UserID AS BuyerID, MAX(BidAmount) AS FinalPrice 
                        FROM bid 
                        WHERE ItemID = ?
                        GROUP BY ItemID";
            $stmt = $conn->prepare($bid_sql);
            $stmt->bind_param("s", $item_id);
            $stmt->execute();
            $stmt->bind_result($buyer_id, $final_price);
            $stmt->fetch();
            $stmt->close();

            if ($buyer_id && $final_price) {
                // Step 3: Update the auction table with the winner and final price
                $update_auction_sql = "UPDATE auction 
                                       SET AuctionStatus = 'completed', PurchasePrice = ?, UserID = ? 
                                       WHERE AuctionID = ?";
                $update_stmt = $conn->prepare($update_auction_sql);
                $update_stmt->bind_param("dss", $final_price, $buyer_id, $auction_id);
                $update_stmt->execute();
                $update_stmt->close();

                // Step 4: Notify both buyer and seller via email
                // Fetch email addresses
                $email_sql = "SELECT u.UserEmail, u.UserID 
                              FROM userpersonalinformation u 
                              WHERE u.UserID IN (?, ?)";
                $email_stmt = $conn->prepare($email_sql);
                $email_stmt->bind_param("ss", $buyer_id, $seller_id);
                $email_stmt->execute();
                $email_result = $email_stmt->get_result();

                $emails = [];
                while ($email_row = $email_result->fetch_assoc()) {
                    $emails[$email_row['UserID']] = $email_row['UserEmail'];
                }

                $buyer_email = $emails[$buyer_id] ?? null;
                $seller_email = $emails[$seller_id] ?? null;

                // Send email
                if ($buyer_email) {
                    $buyer_subject = "Congratulations! You won the auction for $item_name";
                    $buyer_message = "Dear Buyer,\n\nYou have won the auction for '$item_name' with a final price of £$final_price.";
                    mail($buyer_email, $buyer_subject, $buyer_message);
                }

                if ($seller_email) {
                    $seller_subject = "Your auction for $item_name has ended";
                    $seller_message = "Dear Seller,\n\nYour auction for '$item_name' has ended with a winning bid of £$final_price.";
                    mail($seller_email, $seller_subject, $seller_message);
                }
            }
        }
    }

    $conn->close();

    // Sleep for 10 seconds before the next check
    sleep(10);
}
?>
