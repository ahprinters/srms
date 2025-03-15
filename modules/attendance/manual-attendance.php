<?php
session_start();
error_reporting(0);
include('../../includes/config.php');
if(strlen($_SESSION['alogin'])=="")
{   
    header("Location: ../../index.php"); 
}
else
{
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
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SRMS Admin | Manual Attendance</title>
        <link rel="stylesheet" href="../../css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="../../css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="../../css/animate-css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="../../css/lobipanel/lobipanel.min.css" media="screen" >
        <link rel="stylesheet" href="../../css/prism/prism.css" media="screen" >
        <link rel="stylesheet" href="../../css/select2/select2.min.css" >
        <link rel="stylesheet" href="../../css/main.css" media="screen" >
        <script src="../../js/modernizr/modernizr.min.js"></script>
    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
            <?php include('../../includes/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">
                    <?php include('../../includes/leftbar.php');?>
                    <!-- /.left-sidebar -->

                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Manual Attendance</h2>
                                </div>
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="../../dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li>Attendance</li>
                                        <li class="active">Manual Attendance</li>
                                    </ul>
                                </div>
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->

                        <section class="section">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title clearfix">
                                                    <h5 class="pull-left">Mark Attendance</h5>
                                                    <div class="pull-right">
                                                        <?php echo backToDashboardButton(); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <?php if(isset($msg)){ ?>
                                                <div class="alert alert-success left-icon-alert" role="alert">
                                                    <strong>Well done!</strong> <?php echo htmlentities($msg); ?>
                                                </div>
                                                <?php } else if(isset($error)){ ?>
                                                <div class="alert alert-danger left-icon-alert" role="alert">
                                                    <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                                </div>
                                                <?php } ?>
                                                <form class="form-horizontal" method="post">
                                                    <div class="form-group">
                                                        <label for="class" class="col-sm-2 control-label">Class</label>
                                                        <div class="col-sm-10">
                                                            <select name="class" class="form-control" id="class" required>
                                                                <option value="">Select Class</option>
                                                                <?php 
                                                                $sql = "SELECT * FROM tblclasses";
                                                                $query = $dbh->prepare($sql);
                                                                $query->execute();
                                                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                                if($query->rowCount() > 0) {
                                                                    foreach($results as $result) { 
                                                                ?>
                                                                <option value="<?php echo htmlentities($result->id); ?>">
                                                                    <?php echo htmlentities($result->ClassName); ?> - 
                                                                    <?php echo htmlentities($result->Section); ?>
                                                                </option>
                                                                <?php }} ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="date" class="col-sm-2 control-label">Date</label>
                                                        <div class="col-sm-10">
                                                            <input type="date" class="form-control" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="button" id="fetchStudents" class="btn btn-primary">
                                                                <i class="fa fa-search"></i> Fetch Students
                                                            </button>
                                                        </div>
                                                    </div>
                                                    
                                                    <div id="studentList" style="display: none;">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Student ID</th>
                                                                            <th>Student Name</th>
                                                                            <th>Attendance Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="studentTableBody">
                                                                        <!-- Student data will be loaded here via AJAX -->
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            
                                                            <div class="form-group">
                                                                <button type="submit" name="submit" class="btn btn-success">
                                                                    <i class="fa fa-check"></i> Save Attendance
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery -->
        <script src="../../js/jquery/jquery-2.2.4.min.js"></script>
        <script src="../../js/bootstrap/bootstrap.min.js"></script>
        <script src="../../js/pace/pace.min.js"></script>
        <script src="../../js/lobipanel/lobipanel.min.js"></script>
        <script src="../../js/iscroll/iscroll.js"></script>
        <script src="../../js/prism/prism.js"></script>
        <script src="../../js/select2/select2.min.js"></script>
        <script src="../../js/main.js"></script>
        <script>
            $(document).ready(function() {
                // Fetch students when button is clicked
                $('#fetchStudents').click(function() {
                    var classId = $('#class').val();
                    var date = $('#date').val();
                    
                    if(classId === '') {
                        alert('Please select a class');
                        return;
                    }
                    
                    $.ajax({
                        url: 'get-students.php',
                        type: 'GET',
                        data: {
                            class: classId,
                            date: date
                        },
                        dataType: 'json',
                        success: function(data) {
                            if(data.error) {
                                alert(data.error);
                                return;
                            }
                            
                            var html = '';
                            $.each(data, function(index, student) {
                                var present = student.status === 'present' ? 'checked' : '';
                                var absent = student.status === 'absent' || student.status === null ? 'checked' : '';
                                
                                html += '<tr>';
                                html += '<td>' + (index + 1) + '</td>';
                                html += '<td>' + student.StudentId + '<input type="hidden" name="students[]" value="' + student.StudentId + '"></td>';
                                html += '<td>' + student.StudentName + '</td>';
                                html += '<td>';
                                html += '<label class="radio-inline"><input type="radio" name="status[' + index + ']" value="present" ' + present + '> Present</label> ';
                                html += '<label class="radio-inline"><input type="radio" name="status[' + index + ']" value="absent" ' + absent + '> Absent</label>';
                                html += '</td>';
                                html += '</tr>';
                            });
                            
                            $('#studentTableBody').html(html);
                            $('#studentList').show();
                        },
                        error: function(xhr, status, error) {
                            alert('An error occurred: ' + error);
                        }
                    });
                });
            });
        </script>
    </body>
</html>
<?php } ?>