
<?php
// Redirect to the subjects module
header("Location: modules/subjects/manage-subjectcombination.php" . (isset($_GET['del']) ? "?del=" . $_GET['del'] : ""));
exit;
?>

