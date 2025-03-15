<?php
session_start();
error_reporting(0);
include('../../includes/config.php');
if(strlen($_SESSION['alogin'])=="")
{   
    header("Location: ../../index.php"); 
}
else{

// Get student ID from URL
$stid=intval($_GET['stid']);

if(isset($_POST['submit']))
{
    $class=$_POST['class'];
    $subject=$_POST['subject']; 
    $marks=$_POST['marks']; 

    $sql="UPDATE tblresult SET ClassId=:class,SubjectId=:subject,marks=:marks WHERE StudentId=:stid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':class',$class,PDO::PARAM_STR);
    $query->bindParam(':subject',$subject,PDO::PARAM_STR);
    $query->bindParam(':marks',$marks,PDO::PARAM_STR);
    $query->bindParam(':stid',$stid,PDO::PARAM_STR);
    $query->execute();

    $msg="Result info updated successfully";
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SRMS Admin | Edit Student Result</title>
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

                    <!-- ========== LEFT SIDEBAR ========== -->
                    <?php include('../../includes/leftbar.php');?>
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Student Result Info</h2>
                                </div>
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="../../dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li><a href="manage-results.php">Results</a></li>
                                        <li class="active">Edit Result</li>
                                    </ul>
                                </div>
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->

                        <section class="section">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2">
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    <h5>Edit Student Result</h5>
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <?php if($msg){?>
                                                <div class="alert alert-success left-icon-alert" role="alert">
                                                    <strong>Well done!</strong> <?php echo htmlentities($msg); ?>
                                                </div>
                                                <?php } else if($error){?>
                                                <div class="alert alert-danger left-icon-alert" role="alert">
                                                    <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                                </div>
                                                <?php } ?>
                                                <form class="form-horizontal" method="post">
                                                    <?php
                                                    // Fetch student details
                                                    $sql = "SELECT tblstudents.StudentName,tblstudents.RollId,tblstudents.RegDate,tblstudents.StudentId,tblstudents.Status,tblclasses.ClassName,tblclasses.Section from tblresult join tblstudents on tblstudents.StudentId=tblresult.StudentId join tblclasses on tblclasses.id=tblresult.ClassId where tblstudents.StudentId=:stid limit 1";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':stid',$stid,PDO::PARAM_STR);
                                                    $query->execute();
                                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                                    if($query->rowCount() > 0)
                                                    {
                                                    foreach($results as $result)
                                                    {  ?>

                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Student Name</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="studentname" class="form-control" id="default" value="<?php echo htmlentities($result->StudentName)?>" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Roll Id</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="rollid" class="form-control" id="default" value="<?php echo htmlentities($result->RollId)?>" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Class</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="classname" class="form-control" id="default" value="<?php echo htmlentities($result->ClassName)?>(<?php echo htmlentities($result->Section)?>)" readonly>
                                                        </div>
                                                    </div>
                                                    <?php } } ?>

                                                    <?php 
                                                    // Fetch result details
                                                    $sql = "SELECT tblresult.id,tblresult.SubjectId,tblresult.marks,tblsubjects.SubjectName from tblresult join tblsubjects on tblsubjects.id=tblresult.SubjectId where tblresult.StudentId=:stid ";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':stid',$stid,PDO::PARAM_STR);
                                                    $query->execute();
                                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                                    $cnt=1;
                                                    if($query->rowCount() > 0)
                                                    {
                                                    foreach($results as $result)
                                                    {  ?>

                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label"><?php echo htmlentities($result->SubjectName)?></label>
                                                        <div class="col-sm-10">
                                                            <input type="hidden" name="id[]" value="<?php echo htmlentities($result->id)?>">
                                                            <input type="text" name="marks[]" class="form-control" id="marks" value="<?php echo htmlentities($result->marks)?>" required>
                                                        </div>
                                                    </div>

                                                    <?php }} ?>

                                                    <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submit" class="btn btn-primary">Update</button>
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

        <!-- ========== COMMON JS FILES ========== -->
        <script src="../../js/jquery/jquery-2.2.4.min.js"></script>
        <script src="../../js/bootstrap/bootstrap.min.js"></script>
        <script src="../../js/pace/pace.min.js"></script>
        <script src="../../js/lobipanel/lobipanel.min.js"></script>
        <script src="../../js/iscroll/iscroll.js"></script>

        <!-- ========== PAGE JS FILES ========== -->
        <script src="../../js/prism/prism.js"></script>
        <script src="../../js/select2/select2.min.js"></script>

        <!-- ========== THEME JS ========== -->
        <script src="../../js/main.js"></script>
        <script>
            $(function($) {
                $(".js-states").select2();
                $(".js-states-limit").select2({
                    maximumSelectionLength: 2
                });
                $(".js-states-hide").select2({
                    minimumResultsForSearch: Infinity
                });
            });
        </script>
    </body>
</html>
<?php } ?>