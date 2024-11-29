<?php

// Include the database connection script
include 'test_connection.php';

// Start the session
session_start();

// Check if the user is already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    echo '<div class="text-center">You are already logged in! Redirecting to the homepage...</div>';
    header("refresh:5;url=index.php");
    exit();
}

// Ensure POST data is provided
if (isset($_POST['email']) && isset($_POST['password'])) {
    // Retrieve and sanitize input
    $userEmail = mysqli_real_escape_string($conn, $_POST['email']);
    $userPassword = $_POST['password']; // Plain text password from the form
    
    // Prepare the SQL query
    $sql = "SELECT UserEmail, UserPassword FROM Userpersonalinformation WHERE UserEmail = '111@126.com'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();

        // Use password_verify to check the hashed password
        var_dump($row['UserPassword'], $userPassword);

        if (password_verify($userPassword, $row['UserPassword'])) {
            // Successful login
            $_SESSION['logged_in'] = true;
            $_SESSION['email'] = $row['UserEmail']; // Ensure this matches the column in your table
            $_SESSION['account_type'] = $row['AccountType']; // Adjust column names as needed

            echo '<div class="text-center">Login successful! Redirecting to the homepage...</div>';
            header("refresh:5;url=index.php");
        } else {
            // Password mismatch
            echo '<div class="text-center">Invalid password. Please try again.</div>';
            header("refresh:5;url=index.php");
        }
    } else {
        // Email not found
        echo '<div class="text-center">Email not found. Please try again.</div>';
        header("refresh:5;url=index.php");
    }
} else {
    // Missing POST data
    echo '<div class="text-center">Please provide both email and password.</div>';
    header("refresh:5;url=index.php");
}

// Close the database connection
$conn->close();

?>

