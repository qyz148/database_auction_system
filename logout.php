<?php

session_start();

unset($_SESSION['logged_in']);
unset($_SESSION['account_type']);
unset($_SESSION['email']);
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
setcookie(session_name(), "", time() - 360);
session_destroy();


// Redirect to index
header("Location: index.php");

?>