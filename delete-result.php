<?php
// Redirect to the new location
if(isset($_GET['stid'])) {
    $stid = $_GET['stid'];
    header("Location: modules/results/delete-result.php?stid=$stid");
} else {
    header("Location: modules/results/manage-results.php");
}
exit;
?>