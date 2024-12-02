<?php
  // For now, index.php just redirects to browse.php, but you can change this
  // if you like.
  // include("inbox.php");
  echo "<div class='mainPage' style='color:red'>";
  header("Location: browse.php");
  echo "</div>"
?>
