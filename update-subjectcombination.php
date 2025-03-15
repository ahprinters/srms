<?php
// Redirect to the subjects module
header("Location: modules/subjects/update-subjectcombination.php" . (isset($_GET['subjectcombid']) ? "?subjectcombid=" . $_GET['subjectcombid'] : ""));
exit;
?>