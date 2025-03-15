<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])=="") {   
    header("Location: index.php"); 
} else {
    // Check if student ID is provided
    if(isset($_GET['stid'])) {
        $stid = intval($_GET['stid']);
        
        // Get student details
        $sql = "SELECT tblstudents.StudentName, tblstudents.RollId, tblstudents.RegDate, tblstudents.StudentId, tblstudents.Status, tblclasses.ClassName, tblclasses.Section 
                FROM tblstudents 
                JOIN tblclasses ON tblclasses.id = tblstudents.ClassId 
                WHERE tblstudents.StudentId = :stid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':stid', $stid, PDO::PARAM_INT);
        $query->execute();
        $student = $query->fetch(PDO::FETCH_OBJ);
        
        // Get result details
        $sql = "SELECT tblsubjects.SubjectName, tblresult.marks, tblresult.id as resultId 
                FROM tblresult 
                JOIN tblsubjects ON tblsubjects.id = tblresult.SubjectId 
                WHERE tblresult.StudentId = :stid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':stid', $stid, PDO::PARAM_INT);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
    } else {
        $error = "Student ID not provided";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Student Result</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
    <link rel="stylesheet" href="css/prism/prism.css" media="screen">
    <link rel="stylesheet" href="css/main.css" media="screen">
    <script src="js/modernizr/modernizr.min.js"></script>
    <style>
        .result-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .student-info {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .result-table th {
            background-color: #f5f5f5;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .print-btn {
            margin-top: 20px;
        }
    </style>
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">
        <!-- Include top navbar -->
        <?php include('includes/topbar.php'); ?>
        
        <div class="content-wrapper">
            <div class="content-container">
                <!-- Include left sidebar -->
                <?php include('includes/leftbar.php'); ?>
                
                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-6">
                                <h2 class="title">Student Result Details</h2>
                            </div>
                        </div>
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                    <li><a href="manage-results.php">Results</a></li>
                                    <li class="active">View Result</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <section class="section">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel">
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                <h5>View Student Result Info</h5>
                                            </div>
                                        </div>
                                        
                                        <?php if(isset($error)){ ?>
                                        <div class="alert alert-danger left-icon-alert" role="alert">
                                            <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                        </div>
                                        <?php } else { ?>
                                        
                                        <div class="panel-body p-20">
                                            <div class="result-card">
                                                <div class="student-info">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h4><?php echo htmlentities($student->StudentName); ?></h4>
                                                            <p><strong>Roll ID:</strong> <?php echo htmlentities($student->RollId); ?></p>
                                                            <p><strong>Class:</strong> <?php echo htmlentities($student->ClassName); ?> (<?php echo htmlentities($student->Section); ?>)</p>
                                                        </div>
                                                        <div class="col-md-6 text-right">
                                                            <p><strong>Registration Date:</strong> <?php echo htmlentities($student->RegDate); ?></p>
                                                            <p><strong>Status:</strong> 
                                                                <?php if($student->Status==1){
                                                                    echo '<span class="label label-success">Active</span>';
                                                                } else {
                                                                    echo '<span class="label label-danger">Blocked</span>';
                                                                } ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="table-responsive">
                                                    <table class="table table-bordered result-table">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Subject</th>
                                                                <th>Marks</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                            $cnt = 1;
                                                            $totalMarks = 0;
                                                            foreach($results as $result) { 
                                                                $totalMarks += $result->marks;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo htmlentities($cnt); ?></td>
                                                                <td><?php echo htmlentities($result->SubjectName); ?></td>
                                                                <td><?php echo htmlentities($result->marks); ?></td>
                                                            </tr>
                                                            <?php 
                                                                $cnt++;
                                                            } 
                                                            ?>
                                                            <tr class="total-row">
                                                                <td colspan="2">Total Marks</td>
                                                                <td><?php echo htmlentities($totalMarks); ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                
                                                <div class="text-right print-btn">
                                                    <a href="print-result.php?stid=<?php echo htmlentities($student->StudentId); ?>" class="btn btn-success" target="_blank">
                                                        <i class="fa fa-print"></i> Print Result
                                                    </a>
                                                    <a href="manage-results.php" class="btn btn-primary">
                                                        <i class="fa fa-arrow-left"></i> Back to Results
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Common JS files -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/pace/pace.min.js"></script>
    <script src="js/lobipanel/lobipanel.min.js"></script>
    <script src="js/iscroll/iscroll.js"></script>
    
    <!-- Theme JS -->
    <script src="js/main.js"></script>
</body>
</html>
<?php } ?>