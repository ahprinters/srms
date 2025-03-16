<?php
session_start();
error_reporting(0);
include('../../includes/config.php');
if(strlen($_SESSION['alogin'])=="")
{   
    header("Location: ../../index.php"); 
}
else{
    // code for inactive students
    if(isset($_GET['inid']))
    {
        $id=$_GET['inid'];
        $status=0;
        $sql="UPDATE tblstudents SET Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id',$id,PDO::PARAM_STR);
        $query->bindParam(':status',$status,PDO::PARAM_STR);
        $query->execute();
        $msg="Student status changed to inactive";
    }

    // code for active students
    if(isset($_GET['id']))
    {
        $id=$_GET['id'];
        $status=1;
        $sql="UPDATE tblstudents SET Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id',$id,PDO::PARAM_STR);
        $query->bindParam(':status',$status,PDO::PARAM_STR);
        $query->execute();
        $msg="Student status changed to active";
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SRMS Admin | Manage Students</title>
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
            <?php include('../../includes/topbar.php');?>
            <div class="content-wrapper">
                <div class="content-container">
                    <?php include('../../includes/leftbar.php');?>
                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Manage Students</h2>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="add-students.php" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Student</a>
                                </div>
                            </div>
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="../../dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li> Students</li>
                                        <li class="active">Manage Students</li>
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
                                                <div class="panel-title clearfix">
                                                    <h5 class="pull-left">View Students Info</h5>
                                                    <div class="pull-right">
                                                        <a href="../../dashboard.php" class="btn btn-success btn-sm"><i class="fa fa-home"></i> Dashboard</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if($msg){?>
                                            <div class="alert alert-success left-icon-alert" role="alert">
                                                <strong>Well done!</strong><?php echo htmlentities($msg); ?>
                                            </div>
                                            <?php } else if($error){?>
                                            <div class="alert alert-danger left-icon-alert" role="alert">
                                                <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                            </div>
                                            <?php } ?>
                                            <div class="panel-body p-20">
                                                <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Student Name</th>
                                                            <th>Roll Id</th>
                                                            <th>Class</th>
                                                            <th>Reg Date</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        $sql = "SELECT tblstudents.StudentName,tblstudents.RollId,tblstudents.RegDate,tblstudents.StudentId,tblstudents.Status,tblstudents.id,tblclasses.ClassName,tblclasses.Section from tblstudents join tblclasses on tblclasses.id=tblstudents.ClassId";
                                                        $query = $dbh->prepare($sql);
                                                        $query->execute();
                                                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                                                        $cnt=1;
                                                        if($query->rowCount() > 0)
                                                        {
                                                        foreach($results as $result)
                                                        {   ?>
                                                        <tr>
                                                            <td><?php echo htmlentities($cnt);?></td>
                                                            <td><?php echo htmlentities($result->StudentName);?></td>
                                                            <td><?php echo htmlentities($result->RollId);?></td>
                                                            <td><?php echo htmlentities($result->ClassName);?>(<?php echo htmlentities($result->Section);?>)</td>
                                                            <td><?php echo htmlentities($result->RegDate);?></td>
                                                            <td><?php if($result->Status==1){
                                                                echo htmlentities('Active');
                                                                }
                                                                else{
                                                                echo htmlentities('Inactive');
                                                                }
                                                                ?></td>
                                                            <td>
                                                                <a href="edit-student.php?stid=<?php echo htmlentities($result->id);?>"><i class="fa fa-edit" title="Edit Record"></i> </a> 
                                                                <?php if($result->Status==1)
                                                                {?>
                                                                <a href="manage-students.php?inid=<?php echo htmlentities($result->id);?>" onclick="return confirm('Are you sure you want to inactive this student?');"><i class="fa fa-times" title="Inactive Student"></i> </a>
                                                                <?php } else {?>
                                                                <a href="manage-students.php?id=<?php echo htmlentities($result->id);?>" onclick="return confirm('Are you sure you want to active this student?');"><i class="fa fa-check" title="Active Student"></i></a>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <?php $cnt=$cnt+1;}} ?>
                                                    </tbody>
                                                </table>
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
        <script src="../../js/jquery/jquery-2.2.4.min.js"></script>
        <script src="../../js/bootstrap/bootstrap.min.js"></script>
        <script src="../../js/pace/pace.min.js"></script>
        <script src="../../js/lobipanel/lobipanel.min.js"></script>
        <script src="../../js/iscroll/iscroll.js"></script>
        <script src="../../js/prism/prism.js"></script>
        <script src="../../js/select2/select2.min.js"></script>
        <script src="../../js/DataTables/jquery.dataTables.min.js"></script>
        <script src="../../js/DataTables/dataTables.bootstrap.min.js"></script>
        <script src="../../js/main.js"></script>
        <script>
            $(function($) {
                $('#example').DataTable();
                
                $('#example2').DataTable( {
                    "scrollY":        "300px",
                    "scrollCollapse": true,
                    "paging":         false
                } );
                
                $('#example3').DataTable();
            });
        </script>
    </body>
</html>
<?php } ?>