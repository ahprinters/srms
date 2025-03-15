<?php
// Include configuration
require_once('../../includes/config.php');

// Check if user is logged in
requireLogin();

// Process form submission for attendance
if(isset($_POST['submit'])) {
    // Get form data
    $classId = $_POST['class'];
    $date = $_POST['date'];
    $students = isset($_POST['students']) ? $_POST['students'] : [];
    $status = isset($_POST['status']) ? $_POST['status'] : [];
    
    try {
        // Begin transaction
        $dbh->beginTransaction();
        
        // Loop through students and insert/update attendance
        foreach($students as $key => $studentId) {
            $attendanceStatus = isset($status[$key]) ? $status[$key] : 'absent';
            
            // Check if attendance record already exists
            $checkSql = "SELECT id FROM tblattendance WHERE StudentId = :studentId AND AttendanceDate = :date";
            $checkQuery = $dbh->prepare($checkSql);
            $checkQuery->bindParam(':studentId', $studentId, PDO::PARAM_STR);
            $checkQuery->bindParam(':date', $date, PDO::PARAM_STR);
            $checkQuery->execute();
            
            if($checkQuery->rowCount() > 0) {
                // Update existing record
                $row = $checkQuery->fetch(PDO::FETCH_ASSOC);
                $updateSql = "UPDATE tblattendance SET Status = :status WHERE id = :id";
                $updateQuery = $dbh->prepare($updateSql);
                $updateQuery->bindParam(':status', $attendanceStatus, PDO::PARAM_STR);
                $updateQuery->bindParam(':id', $row['id'], PDO::PARAM_INT);
                $updateQuery->execute();
            } else {
                // Insert new record
                $insertSql = "INSERT INTO tblattendance (StudentId, ClassId, Status, AttendanceDate) VALUES (:studentId, :classId, :status, :date)";
                $insertQuery = $dbh->prepare($insertSql);
                $insertQuery->bindParam(':studentId', $studentId, PDO::PARAM_STR);
                $insertQuery->bindParam(':classId', $classId, PDO::PARAM_INT);
                $insertQuery->bindParam(':status', $attendanceStatus, PDO::PARAM_STR);
                $insertQuery->bindParam(':date', $date, PDO::PARAM_STR);
                $insertQuery->execute();
            }
        }
        
        // Commit transaction
        $dbh->commit();
        $msg = "Attendance recorded successfully";
    } catch(PDOException $e) {
        // Rollback transaction on error
        $dbh->rollBack();
        $error = "Error: " . $e->getMessage();
    }
}

// Include header and sidebar
$pageTitle = "Manual Attendance";
require_once('../../includes/header.php');
require_once('../../includes/sidebar.php');
?>

<div class="main-content">
    <nav class="navbar navbar-expand-lg navbar-light mb-4">
        <div class="container-fluid">
            <button class="btn btn-dark d-lg-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item">Attendance</li>
                    <li class="breadcrumb-item active" aria-current="page">Manual Attendance</li>
                </ol>
            </nav>
        </div>
    </nav>
    
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i> Manual Attendance
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if(isset($msg)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> <?php echo $msg; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="post" id="attendanceForm">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="class" class="form-label">Select Class</label>
                                    <select class="form-select" id="class" name="class" required>
                                        <option value="">Select Class</option>
                                        <?php
                                        $sql = "SELECT id, ClassName, Section FROM tblclasses";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $classes = $query->fetchAll(PDO::FETCH_OBJ);
                                        
                                        if($query->rowCount() > 0) {
                                            foreach($classes as $class) {
                                                echo '<option value="' . $class->id . '">' . htmlentities($class->ClassName) . ' - ' . htmlentities($class->Section) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <button type="button" id="fetchStudents" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i> Fetch Students
                                </button>
                            </div>
                            
                            <div id="studentList" class="mt-4" style="display: none;">
                                <h5 class="mb-3">Mark Attendance</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="15%">Student ID</th>
                                                <th width="30%">Student Name</th>
                                                <th width="50%">Attendance Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="studentTableBody">
                                            <!-- Student data will be loaded here via AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-3">
                                    <button type="submit" name="submit" class="btn btn-success">
                                        <i class="fas fa-save me-2"></i> Save Attendance
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch students button click handler
    document.getElementById('fetchStudents').addEventListener('click', function() {
        const classId = document.getElementById('class').value;
        const date = document.getElementById('date').value;
        
        if(!classId) {
            alert('Please select a class');
            return;
        }
        
        if(!date) {
            alert('Please select a date');
            return;
        }
        
        // Fetch students via AJAX
        fetch(`<?php echo BASE_URL; ?>modules/attendance/get-students.php?class=${classId}&date=${date}`)
            .then(response => response.json())
            .then(data => {
                const studentTableBody = document.getElementById('studentTableBody');
                studentTableBody.innerHTML = '';
                
                if(data.length > 0) {
                    data.forEach((student, index) => {
                        const row = document.createElement('tr');
                        
                        // Create table cells
                        row.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${student.StudentId}
                                <input type="hidden" name="students[]" value="${student.StudentId}">
                            </td>
                            <td>${student.StudentName}</td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status[${index}]" id="present${index}" value="present" ${student.status === 'present' ? 'checked' : ''}>
                                    <label class="form-check-label" for="present${index}">Present</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status[${index}]" id="absent${index}" value="absent" ${student.status === 'absent' || !student.status ? 'checked' : ''}>
                                    <label class="form-check-label" for="absent${index}">Absent</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status[${index}]" id="late${index}" value="late" ${student.status === 'late' ? 'checked' : ''}>
                                    <label class="form-check-label" for="late${index}">Late</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status[${index}]" id="excused${index}" value="excused" ${student.status === 'excused' ? 'checked' : ''}>
                                    <label class="form-check-label" for="excused${index}">Excused</label>
                                </div>
                            </td>
                        `;
                        
                        studentTableBody.appendChild(row);
                    });
                    
                    // Show the student list
                    document.getElementById('studentList').style.display = 'block';
                } else {
                    studentTableBody.innerHTML = '<tr><td colspan="4" class="text-center">No students found in this class</td></tr>';
                    document.getElementById('studentList').style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while fetching students');
            });
    });
});
</script>

<?php
require_once('../../includes/footer.php');
?>