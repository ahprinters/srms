<?php
session_start();
error_reporting(0);
include('../../includes/config.php');

// Check if user is logged in
if(strlen($_SESSION['alogin'])=="") {   
    header("Location: ../../index.php"); 
} else {
    // Check if student ID is provided
    if(isset($_GET['stid'])) {
        $stid = intval($_GET['stid']);
        
        // Delete the student's results
        $sql = "DELETE FROM tblresult WHERE StudentId = :stid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':stid', $stid, PDO::PARAM_INT);
        $query->execute();
        
        if($query) {
            $_SESSION['msg'] = "Student result deleted successfully";
        } else {
            $_SESSION['error'] = "Error: Something went wrong. Please try again";
        }
        
        // Redirect back to manage results page
        header("Location: manage-results.php");
        exit();
    } else {
        $_SESSION['error'] = "Student ID not provided";
        header("Location: manage-results.php");
        exit();
    }
}
?>