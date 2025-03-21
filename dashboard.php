<?php
// Include configuration
require_once('includes/config.php');

// Check if user is logged in
requireLogin();

// Include header and sidebar
require_once('includes/header.php');
require_once('includes/sidebar.php');

// Include dashboard data functions
require_once('includes/dashboard-data.php');
?>

<!-- Main Content -->
<div class="main-content">
    <nav class="navbar navbar-expand-lg navbar-light mb-4">
        <div class="container-fluid">
            <button class="btn btn-dark d-lg-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
    </nav>
    
    <!-- Notice Marquee Section -->
    <div class="container-fluid mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bell me-2"></i> Latest Notifications
                </h5>
            </div>
            <div class="card-body">
                <marquee behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
                    <?php 
                    $sql = "SELECT noticeTitle, id FROM tblnotice ORDER BY postingDate DESC LIMIT 5";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    $cnt = 1;
                    if($query->rowCount() > 0) {
                        foreach($results as $result) { 
                            echo '<span class="text-bold"><a href="modules/notice/notice-details.php?nid='.htmlentities($result->id).'">'.htmlentities($result->noticeTitle).'</a></span>';
                            if($cnt < $query->rowCount()) {
                                echo ' &nbsp; | &nbsp; ';
                            }
                            $cnt++;
                        }
                    } else {
                        echo "No notifications available";
                    }
                    ?>
                </marquee>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <!-- Dashboard Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-0">Total Students</h5>
                                <h2 class="mt-2 mb-0"><?php echo getTotalStudents($dbh); ?></h2>
                            </div>
                            <i class="fas fa-users fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-0">Total Classes</h5>
                                <h2 class="mt-2 mb-0"><?php echo getTotalClasses($dbh); ?></h2>
                            </div>
                            <i class="fas fa-chalkboard fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-0">Total Subjects</h5>
                                <h2 class="mt-2 mb-0"><?php echo getTotalSubjects($dbh); ?></h2>
                            </div>
                            <i class="fas fa-book fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-0">Results Declared</h5>
                                <h2 class="mt-2 mb-0"><?php echo getTotalResults($dbh); ?></h2>
                            </div>
                            <i class="fas fa-chart-bar fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Notices and Quick Links -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bullhorn me-2"></i> Recent Notices
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $notices = getRecentNotices($dbh);
                        if(count($notices) > 0) {
                            foreach($notices as $notice) {
                                ?>
                                <div class="notice-item mb-3 pb-3 border-bottom">
                                    <h6><?php echo htmlentities($notice->title); ?></h6>
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-calendar-alt me-1"></i> 
                                        <?php echo date('M d, Y', strtotime($notice->created_at)); ?>
                                    </p>
                                    <p class="mb-0">
                                        <?php 
                                        // Fix for undefined property warning
                                        if(isset($notice->message) && !is_null($notice->message)) {
                                            echo htmlentities(substr($notice->message, 0, 100)) . '...';
                                        } else {
                                            echo 'No details available';
                                        }
                                        ?>
                                    </p>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<p class="text-center mb-0">No recent notices</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-link me-2"></i> Quick Links
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <a href="modules/students/add-students.php" class="btn btn-primary w-100">
                                    <i class="fas fa-user-plus me-2"></i> Add Student
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="modules/results/add-result.php" class="btn btn-success w-100">
                                    <i class="fas fa-plus-circle me-2"></i> Add Result
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="modules/classes/manage-classes.php" class="btn btn-info w-100 text-white">
                                    <i class="fas fa-chalkboard me-2"></i> Manage Classes
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="modules/results/find-result.php" class="btn btn-warning w-100">
                                    <i class="fas fa-search me-2"></i> Find Result
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Upcoming Events Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar-alt me-2"></i> Upcoming Events
                        </h5>
                        <a href="modules/events/manage-events.php" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php 
                            // Get upcoming events
                            $sql = "SELECT * FROM tblevents WHERE eventDate >= CURDATE() ORDER BY eventDate ASC, eventTime ASC LIMIT 5";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $events = $query->fetchAll(PDO::FETCH_OBJ);
                            
                            if($query->rowCount() > 0) {
                                foreach($events as $event) { 
                                    // Format date
                                    $eventDate = new DateTime($event->eventDate);
                                    $formattedDate = $eventDate->format('M d, Y');
                                    
                                    // Format time
                                    $eventTime = new DateTime($event->eventTime);
                                    $formattedTime = $eventTime->format('h:i A');
                            ?>
                            <div class="list-group-item">
                                <div class="row">
                                    <div class="col-md-2 text-center">
                                        <div style="font-size: 24px; font-weight: bold;"><?php echo $eventDate->format('d'); ?></div>
                                        <div><?php echo $eventDate->format('M'); ?></div>
                                    </div>
                                    <div class="col-md-10">
                                        <h6 class="mb-1"><?php echo htmlentities($event->eventTitle); ?></h6>
                                        <p class="mb-1 small">
                                            <span class="badge bg-primary"><?php echo htmlentities($event->eventType); ?></span>
                                            <i class="fas fa-clock me-1"></i> <?php echo $formattedTime; ?> | 
                                            <i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlentities($event->eventLocation); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php 
                                }
                            } else { 
                            ?>
                            <div class="list-group-item">
                                <p class="text-center mb-0">No upcoming events</p>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="text-center mt-3">
                            <a href="modules/events/event-calendar.php" class="btn btn-sm btn-info text-white">
                                <i class="fas fa-calendar"></i> Calendar View
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- You can add another widget here in the second column -->
            <div class="col-md-6">
                <!-- Another widget can go here -->
            </div>
        </div>
    </div>
</div>

<?php require_once('includes/footer.php'); ?>

