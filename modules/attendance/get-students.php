<?php
// Include configuration
require_once('../../includes/config.php');

// Check if user is logged in
requireLogin();

// Set content type to JSON
header('Content-Type: application/json');

// Check if required parameters are provided
if(!isset($_GET['class']) || !isset($_GET['date'])) {
    echo json_encode(['error' => 'Missing required parameters']);
    exit;
}

// Get parameters
$classId = $_GET['class'];
$date = $_GET['date'];

try {
    // Get students from the selected class
    $sql = "SELECT s.StudentId, s.StudentName 
            FROM tblstudents s 
            WHERE s.ClassId = :classId 
            ORDER BY s.StudentName ASC";
    $query = $dbh->prepare($sql);
    $query->bindParam(':classId', $classId, PDO::PARAM_INT);
    $query->execute();
    $students = $query->fetchAll(PDO::FETCH_ASSOC);
    
    // Get existing attendance records for the selected date
    $attendanceSql = "SELECT StudentId, Status 
                      FROM tblattendance 
                      WHERE ClassId = :classId AND AttendanceDate = :date";
    $attendanceQuery = $dbh->prepare($attendanceSql);
    $attendanceQuery->bindParam(':classId', $classId, PDO::PARAM_INT);
    $attendanceQuery->bindParam(':date', $date, PDO::PARAM_STR);
    $attendanceQuery->execute();
    
    // Create a map of student IDs to attendance status
    $attendanceMap = [];
    while($row = $attendanceQuery->fetch(PDO::FETCH_ASSOC)) {
        $attendanceMap[$row['StudentId']] = $row['Status'];
    }
    
    // Add attendance status to student data
    foreach($students as &$student) {
        $student['status'] = isset($attendanceMap[$student['StudentId']]) ? $attendanceMap[$student['StudentId']] : null;
    }
    
    echo json_encode($students);
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>