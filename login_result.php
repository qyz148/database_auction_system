<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to login.
// Notify user of success/failure and redirect/give navigation options.
include 'test_connection.php';
// For now, I will just set session variables and redirect.

session_start();
$_logged_in = $_SESSION['logged_in'];
if($_logged_in == null || $_logged_in == false){
    $_SESSION['logged_in'] = false;
    $_SESSION['username'] = null;
    $_SESSION['account_type'] = null;  

    // Sql Query
    $sql = "select * from xx where usernane = " +$_POST['username'] + " and password = " + $_POST['password'] + ";";
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
        
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $row["username"];
        $_SESSION['account_type'] = $row["accountType"];
      }
    } else {
      echo "0 results";
    }
    
    $conn->close();
    echo('<div class="text-center">You are now logged in! You will be redirected shortly.</div>');

    header("refresh:5;url=index.php");

}




// Redirect to index after 5 seconds

?>