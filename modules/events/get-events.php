<?php
session_start();
error_reporting(0);
include('../../includes/config.php');

// Check if user is logged in
if(strlen($_SESSION['alogin'])=="") {   
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    exit;
}

// Get date range from request
$start = isset($_POST['start']) ? $_POST['start'] : date('Y-m-d');
$end = isset($_POST['end']) ? $_POST['end'] : date('Y-m-d', strtotime('+30 days'));

try {
    // Fetch events from database
    $sql = "SELECT * FROM tblevents WHERE eventDate BETWEEN :start AND :end ORDER BY eventDate, eventTime";
    $query = $dbh->prepare($sql);
    $query->bindParam(':start', $start, PDO::PARAM_STR);
    $query->bindParam(':end', $end, PDO::PARAM_STR);
    $query->execute();
    $events = $query->fetchAll(PDO::FETCH_OBJ);
    
    echo json_encode(['success' => true, 'data' => $events]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>