<?php
// Redirect to the notice module
header("Location: modules/notice/notice-details.php" . (isset($_GET['nid']) ? "?nid=" . $_GET['nid'] : ""));
exit;
?>
