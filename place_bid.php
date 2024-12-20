<?php
include("test_connection.php");
include("utilities.php");
// TODO: Extract $_POST variables, check they're OK, and attempt to make a bid.
// Notify user of success/failure and redirect/give navigation options.

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Extract and sanitize POST variables
    $account_type = filter_input(INPUT_POST, 'account_type', FILTER_SANITIZE_ENCODED);
    $item_id = filter_input(INPUT_POST, 'item_id', FILTER_SANITIZE_ENCODED);
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_ENCODED);
    $bid_amount = filter_input(INPUT_POST, 'bid_amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    
    // Check if the variables are valid
    if ($item_id && $user_id && $bid_amount && $bid_amount > 0) {
        // Connect to the database
        $conn = new mysqli("localhost", "root", "", "auction_system");
        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }

        // Check the current highest bid and closing date of the item
        $sql = "SELECT CurrentBid, ClosingDate FROM item WHERE ItemID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $item_id);
        $stmt->execute();
        $stmt->bind_result($current_bid, $closing_date);
        $stmt->fetch();
        $stmt->close();
        date_default_timezone_set('UTC');
        $now = new DateTime();
        $closing_date_time = new DateTime($closing_date);
        // echo(date_format($now, 'j M H:i:s'));
        // echo "<br>";
        // echo(date_format($closing_date_time, 'j M H:i:s'));
        // Check if the auction has already ended
        if ($now > $closing_date_time) {
            echo "The auction for this item has already ended.";
        } elseif ($bid_amount <= $current_bid) {
            // Check if the bid amount is higher than the current bid
            echo "Your bid must be higher than the current bid.";
            // depends on user type, jump back to different page
            if($account_type=="seller"){
                header("refresh:2;mylistings.php");
            }else{
                header("refresh:2;mybids.php");
            }
        } else {
            // Insert the new bid into the bid table
            $sql = "INSERT INTO bid (BidID, UserID, ItemID, BidAmount, TimeOfBid) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $bidID = uuid4();
            $stmt->bind_param("ssss", $bidID, $user_id, $item_id, $bid_amount);

            if ($stmt->execute()) {
                // Update the CurrentBid in the item table
                $update_sql = "UPDATE item SET CurrentBid = ? WHERE ItemID = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("ds", $bid_amount, $item_id);
                $update_stmt->execute();
                $update_stmt->close();

                echo "Bid placed successfully!";
                // depends on user type, jump back to different page
                if($account_type=="seller"){
                    header("refresh:2;mylistings.php");
                }else{
                    header("refresh:2;mybids.php");
                }
            } else {
                echo "Failed to place bid. Please try again.";
                // depends on user type, jump back to different page
                if($account_type=="seller"){
                    header("refresh:2;mylistings.php");
                }else{
                    header("refresh:2;mybids.php");
                }
            }
            $stmt->close();
        }

        $conn->close();
    } else {
        // Notify user of invalid input
        echo "Invalid input. Please check your data and try again.";
    }
} else {
    // Redirect or provide navigation options if not a POST request
    echo "Invalid request method.";
}
?>
