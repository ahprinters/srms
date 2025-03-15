<div class="left-sidebar bg-black-300 box-shadow">
    <div class="sidebar-content">
        <div class="user-info closed">
            <img src="<?php echo isset($BASE_URL) ? $BASE_URL : '../'; ?>images/user.png" alt="User Image" class="img-circle profile-img">
            <h6 class="title">Admin</h6>
            <small class="info">Administrator</small>
        </div>
        <div class="sidebar-nav">
            <ul class="side-nav color-gray">
                <li class="nav-header">
                    <span class="">Main Category</span>
                </li>
                <li>
                    <a href="<?php echo isset($BASE_URL) ? $BASE_URL.'dashboard.php' : '../dashboard.php'; ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                </li>

                <li class="has-children">
                    <a href="#"><i class="fa fa-file-text"></i> <span>Student Classes</span> <i class="fa fa-angle-right arrow"></i></a>
                    <ul class="child-nav">
                        <li><a href="create-class.php"><i class="fa fa-plus"></i> <span>Create Class</span></a></li>
                        <li><a href="manage-classes.php"><i class="fa fa fa-server"></i> <span>Manage Classes</span></a></li>
                    </ul>
                </li>

                <li class="has-children">
                    <a href="#"><i class="fa fa-file-text"></i> <span>Subjects</span> <i class="fa fa-angle-right arrow"></i></a>
                    <ul class="child-nav">
                        <li><a href="create-subject.php"><i class="fa fa-plus"></i> <span>Create Subject</span></a></li>
                        <li><a href="manage-subjects.php"><i class="fa fa fa-server"></i> <span>Manage Subjects</span></a></li>
                        <li><a href="add-subjectcombination.php"><i class="fa fa-plus"></i> <span>Add Subject Combination</span></a></li>
                        <li><a href="manage-subjectcombination.php"><i class="fa fa fa-server"></i> <span>Manage Subject Combination</span></a></li>
                    </ul>
                </li>

                <li class="has-children">
                    <a href="#"><i class="fa fa-users"></i> <span>Students</span> <i class="fa fa-angle-right arrow"></i></a>
                    <ul class="child-nav">
                        <li><a href="add-students.php"><i class="fa fa-plus"></i> <span>Add Students</span></a></li>
                        <li><a href="manage-students.php"><i class="fa fa fa-server"></i> <span>Manage Students</span></a></li>
                    </ul>
                </li>

                <li class="has-children">
                    <a href="#"><i class="fa fa-info-circle"></i> <span>Result</span> <i class="fa fa-angle-right arrow"></i></a>
                    <ul class="child-nav">
                        <li><a href="add-result.php"><i class="fa fa-plus"></i> <span>Add Result</span></a></li>
                        <li><a href="manage-results.php"><i class="fa fa fa-server"></i> <span>Manage Result</span></a></li>
                    </ul>
                </li>

                <li class="has-children">
                    <a href="#"><i class="fa fa-bell"></i> <span>Student Attendance</span> <i class="fa fa-angle-right arrow"></i></a>
                    <ul class="child-nav">
                        <li><a href="biometric-attendance.php"><i class="fa fa-plus"></i> <span>Biometric Attendance</span></a></li>
                        <li><a href="id-card-attendance.php"><i class="fa fa-plus"></i> <span>ID Card Attendance</span></a></li>
                        <li><a href="manage-attendance.php"><i class="fa fa fa-server"></i> <span>Manage Attendance</span></a></li>
                        <li><a href="attendance-report.php"><i class="fa fa fa-server"></i> <span>Attendance Reports</span></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>