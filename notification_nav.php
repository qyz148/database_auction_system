<?php

include("test_connection.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $unread_count = 0;
} else {
    $user_id = $_SESSION['user_id'];
    // Query to count all notifications
    $sql = "SELECT COUNT(*) AS unread_count FROM inbox WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->bind_result($unread_count);
    $stmt->fetch();
    $stmt->close();
}
?>

<!-- Notification Nav -->
<nav class="navbar navbar-expand-lg navbar-light " style="position:absolute;width:100%">
    <!-- <a class="navbar-brand" href="index.php">Auction System</a> -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" href="inbox.php">
                Notifications 
                <?php if (isset($unread_count) && $unread_count > 0): ?>
                    <span class="badge badge-primary"><?php echo $unread_count; ?></span>
                <?php endif; ?>
            </a>
        </li>
    </ul>
</nav>
