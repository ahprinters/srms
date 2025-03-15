<!-- Sidebar -->
<div class="sidebar" style="background-color: #333; background-image: none;">
    <div class="sidebar-header">
        <h3>SRMS</h3>
    </div>
    
    <div class="collapse-menu" id="collapseAll">
        <i class="fas fa-compress-alt me-2"></i> Collapse Menu
    </div>
    
    <div class="sidebar-content">
        <div class="category-title">DASHBOARD</div>
        <ul class="sidebar-menu">
            <li>
                <a href="<?php echo BASE_URL; ?>dashboard.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
        </ul>
        
        <div class="category-title">ACADEMICS</div>
        <ul class="sidebar-menu">
            <li class="has-submenu <?php echo (strpos($_SERVER['PHP_SELF'], '/students/') !== false) ? 'open' : ''; ?>">
                <a href="#">
                    <i class="fas fa-user-graduate"></i> Students
                </a>
                <ul class="submenu" style="<?php echo (strpos($_SERVER['PHP_SELF'], '/students/') !== false) ? 'max-height: 200px;' : ''; ?>">
                    <li>
                        <a href="<?php echo BASE_URL; ?>modules/students/add-students.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'add-students.php') ? 'active' : ''; ?>">
                            <i class="fas fa-user-plus"></i> Add Student
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>modules/students/manage-students.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'manage-students.php') ? 'active' : ''; ?>">
                            <i class="fas fa-users"></i> Manage Students
                        </a>
                    </li>
                </ul>
            </li>
            
            <li class="has-submenu <?php echo (strpos($_SERVER['PHP_SELF'], '/classes/') !== false) ? 'open' : ''; ?>">
                <a href="#">
                    <i class="fas fa-graduation-cap"></i> Classes
                </a>
                <ul class="submenu" style="<?php echo (strpos($_SERVER['PHP_SELF'], '/classes/') !== false) ? 'max-height: 200px;' : ''; ?>">
                    <li>
                        <a href="<?php echo BASE_URL; ?>modules/classes/create-class.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'create-class.php') ? 'active' : ''; ?>">
                            <i class="fas fa-plus-circle"></i> Create Class
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>modules/classes/manage-classes.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'manage-classes.php') ? 'active' : ''; ?>">
                            <i class="fas fa-list"></i> Manage Classes
                        </a>
                    </li>
                </ul>
            </li>
            
            <li class="has-submenu <?php echo (strpos($_SERVER['PHP_SELF'], '/subjects/') !== false) ? 'open' : ''; ?>">
                <a href="#">
                    <i class="fas fa-book"></i> Subjects
                </a>
                <ul class="submenu" style="<?php echo (strpos($_SERVER['PHP_SELF'], '/subjects/') !== false) ? 'max-height: 200px;' : ''; ?>">
                    <li>
                        <a href="<?php echo BASE_URL; ?>modules/subjects/create-subject.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'create-subject.php') ? 'active' : ''; ?>">
                            <i class="fas fa-plus-circle"></i> Create Subject
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>modules/subjects/manage-subjects.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'manage-subjects.php') ? 'active' : ''; ?>">
                            <i class="fas fa-list"></i> Manage Subjects
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>modules/subjects/add-subject-combination.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'add-subject-combination.php') ? 'active' : ''; ?>">
                            <i class="fas fa-link"></i> Add Subject Combination
                        </a>
                    </li>
                </ul>
            </li>
            
            <li class="has-submenu <?php echo (strpos($_SERVER['PHP_SELF'], '/results/') !== false) ? 'open' : ''; ?>">
                <a href="#">
                    <i class="fas fa-chart-bar"></i> Results
                </a>
                <ul class="submenu" style="<?php echo (strpos($_SERVER['PHP_SELF'], '/results/') !== false) ? 'max-height: 200px;' : ''; ?>">
                    <li>
                        <a href="<?php echo BASE_URL; ?>modules/results/add-result.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'add-result.php') ? 'active' : ''; ?>">
                            <i class="fas fa-plus-circle"></i> Add Result
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>modules/results/manage-results.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'manage-results.php') ? 'active' : ''; ?>">
                            <i class="fas fa-list"></i> Manage Results
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        
        <!-- In the attendance section of the sidebar -->
        <div class="category-title">ATTENDANCE</div>
        <ul class="sidebar-menu">
            <li class="has-submenu <?php echo (strpos($_SERVER['PHP_SELF'], '/attendance/') !== false) ? 'open' : ''; ?>">
                <a href="#">
                    <i class="fas fa-clipboard-check"></i> Student Attendance
                </a>
                <ul class="submenu" style="<?php echo (strpos($_SERVER['PHP_SELF'], '/attendance/') !== false) ? 'max-height: 200px;' : ''; ?>">
                    <li>
                        <a href="<?php echo BASE_URL; ?>modules/attendance/manual-attendance.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'manual-attendance.php') ? 'active' : ''; ?>">
                            <i class="fas fa-edit"></i> Manual Attendance
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>biometric-attendance.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'biometric-attendance.php') ? 'active' : ''; ?>">
                            <i class="fas fa-fingerprint"></i> Biometric Attendance
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>id-card-attendance.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'id-card-attendance.php') ? 'active' : ''; ?>">
                            <i class="fas fa-id-card"></i> ID Card Attendance
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        
        <div class="category-title">EVENTS</div>
        <ul class="sidebar-menu">
            <li class="has-submenu <?php echo (strpos($_SERVER['PHP_SELF'], '/events/') !== false) ? 'open' : ''; ?>">
                <a href="#">
                    <i class="fas fa-calendar-alt"></i> Event Management
                </a>
                <ul class="submenu" style="<?php echo (strpos($_SERVER['PHP_SELF'], '/events/') !== false) ? 'max-height: 200px;' : ''; ?>">
                    <li>
                        <a href="<?php echo BASE_URL; ?>modules/events/add-event.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'add-event.php') ? 'active' : ''; ?>">
                            <i class="fas fa-plus-circle"></i> Add Event
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>modules/events/manage-events.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'manage-events.php') ? 'active' : ''; ?>">
                            <i class="fas fa-cog"></i> Manage Events
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>modules/events/event-calendar.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'event-calendar.php') ? 'active' : ''; ?>">
                            <i class="fas fa-calendar-week"></i> Event Calendar
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        
        <div class="category-title">SYSTEM</div>
        <ul class="sidebar-menu">
            <li>
                <a href="<?php echo BASE_URL; ?>admin/manage-notifications.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'manage-notifications.php') ? 'active' : ''; ?>">
                    <i class="fas fa-bell"></i> Notifications
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>admin/system-settings.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'system-settings.php') ? 'active' : ''; ?>">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>admin/profile.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : ''; ?>">
                    <i class="fas fa-user-circle"></i> Profile
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>admin/change-password.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'change-password.php') ? 'active' : ''; ?>">
                    <i class="fas fa-key"></i> Change Password
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</div>