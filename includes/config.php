<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'srms');

// Base URL configuration
// Define base URL for the application
if (!defined('BASE_URL')) {
    // Determine if we're in a subdirectory
    $script_name = $_SERVER['SCRIPT_NAME'];
    $base_dir = dirname($script_name);
    
    // If we're in the root directory
    if ($base_dir == '/' || $base_dir == '\\') {
        define('BASE_URL', '/');
    } else {
        // We're in a subdirectory
        $base_url = str_replace('\\', '/', $base_dir);
        if (substr($base_url, -1) != '/') {
            $base_url .= '/';
        }
        define('BASE_URL', $base_url);
    }
}

// Helper function to get dashboard URL
function getDashboardUrl() {
    $current_path = $_SERVER['SCRIPT_NAME'];
    $depth = substr_count($current_path, '/') - 1;
    
    if ($depth <= 0) {
        return 'dashboard.php';
    } else {
        return str_repeat('../', $depth) . 'dashboard.php';
    }
}

// Helper function to create a back to dashboard button
function backToDashboardButton($additionalClasses = '') {
    $dashboard_url = getDashboardUrl();
    return '<a href="' . $dashboard_url . '" class="btn btn-primary ' . $additionalClasses . '"><i class="fa fa-dashboard"></i> Back to Dashboard</a>';
}

// Establish database connection
try {
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    exit("Error: " . $e->getMessage());
}

// Session start - only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['alogin']);
}

// Function to redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('location:index.php');
        exit();
    }
}
?>