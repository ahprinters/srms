<?php
/**
 * Dashboard Data Functions
 * Contains functions to retrieve data for the dashboard
 */

// Function to get total number of students
function getTotalStudents($dbh) {
    try {
        $sql = "SELECT COUNT(*) as total FROM tblstudents";
        $query = $dbh->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result->total;
    } catch(PDOException $e) {
        return 0;
    }
}

// Function to get total number of classes
function getTotalClasses($dbh) {
    try {
        $sql = "SELECT COUNT(*) as total FROM tblclasses";
        $query = $dbh->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result->total;
    } catch(PDOException $e) {
        return 0;
    }
}

// Function to get total number of subjects
function getTotalSubjects($dbh) {
    try {
        $sql = "SELECT COUNT(*) as total FROM tblsubjects";
        $query = $dbh->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result->total;
    } catch(PDOException $e) {
        return 0;
    }
}

// Function to get total number of results declared
function getTotalResults($dbh) {
    try {
        $sql = "SELECT COUNT(DISTINCT StudentId) as total FROM tblresult";
        $query = $dbh->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result->total;
    } catch(PDOException $e) {
        return 0;
    }
}

// Function to get recent notices
function getRecentNotices($dbh) {
    try {
        $sql = "SELECT * FROM tblnotice ORDER BY created_at DESC LIMIT 5";
        $query = $dbh->prepare($sql);
        $query->execute();
        $notices = $query->fetchAll(PDO::FETCH_OBJ);
        return $notices;
    } catch(PDOException $e) {
        return [];
    }
}
?>