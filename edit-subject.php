<?php
// Redirect to the subjects module
header("Location: modules/subjects/edit-subject.php" . (isset($_GET['subjectid']) ? "?subjectid=" . $_GET['subjectid'] : ""));
exit;
?>
