<?php include("test_connection.php")?>

<?php


// TODO: Extract $_POST variables, check they're OK, and attempt to make a bid.
// Notify user of success/failure and redirect/give navigation options.

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Extract and sanitize POST variables
    $item_id = filter_input(INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT);
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
    $bid_amount = filter_input(INPUT_POST, 'bid_amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // Check if the variables are valid
    if ($item_id && $user_id && $bid_amount && $bid_amount > 0) {
        // TODO: Attempt to make a bid in the database
        // Assuming a function make_bid($item_id, $user_id, $bid_amount) exists
        $result = make_bid($item_id, $user_id, $bid_amount);

        if ($result) {
            // Notify user of success
            echo "Bid placed successfully!";
        } else {
            // Notify user of failure
            echo "Failed to place bid. Please try again.";
        }
    } else {
        // Notify user of invalid input
        echo "Invalid input. Please check your data and try again.";
    }
} else {
    // Redirect or provide navigation options if not a POST request
    echo "Invalid request method.";
}

?>