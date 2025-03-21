<?php
session_start();
error_reporting(0);
include('../../includes/config.php');
if(strlen($_SESSION['alogin'])=="")
{   
    header("Location: ../../index.php"); 
}
else{
    // code for delete subject combination
    if(isset($_GET['del']))
    {
        $id=$_GET['del'];
        $sql="DELETE FROM tblsubjectcombination WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id',$id,PDO::PARAM_STR);
        $query->execute();
        $msg="Subject Combination deleted successfully";
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SRMS Admin | Manage Subject Combination</title>
        <link rel="stylesheet" href="../../css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="../../css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="../../css/animate-css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="../../css/lobipanel/lobipanel.min.css" media="screen" >
        <link rel="stylesheet" href="../../css/prism/prism.css" media="screen" >
        <link rel="stylesheet" href="../../css/select2/select2.min.css" >
        <link rel="stylesheet" href="../../css/main.css" media="screen" >
        <link rel="stylesheet" href="../../css/sidebar.css" media="screen" >
        <script src="../../js/modernizr/modernizr.min.js"></script>
    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">
            <?php include('../../includes/topbar.php');?>
            <div class="content-wrapper">
                <div class="content-container">
                    <?php include('../../includes/sidebar.php');?>
                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Manage Subject Combination</h2>
                                </div>
                            </div>
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="../../dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li> Subjects</li>
                                        <li class="active">Manage Subject Combination</li>
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
                                                <?php echo backToDashboardButton(); ?>
                                                    <h5>View Subject Combination Info</h5>
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
                                                            <th>Class and Section</th>
                                                            <th>Subject </th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        $sql = "SELECT tblclasses.ClassName,tblclasses.Section,tblsubjects.SubjectName,tblsubjectcombination.id as scid,tblsubjectcombination.status from tblsubjectcombination join tblclasses on tblclasses.id=tblsubjectcombination.ClassId join tblsubjects on tblsubjects.id=tblsubjectcombination.SubjectId";
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
                                                            <td><?php echo htmlentities($result->ClassName);?> &nbsp; Section-<?php echo htmlentities($result->Section);?></td>
                                                            <td><?php echo htmlentities($result->SubjectName);?></td>
                                                            <td><?php $stts=$result->status;
                                                            if($stts=='0')
                                                            {
                                                            echo htmlentities('Inactive');
                                                            }
                                                            else
                                                            {
                                                            echo htmlentities('Active');
                                                            }
                                                            ?></td>
                                                            <td>
                                                                <?php if($stts=='0')
                                                                { ?>
                                                                <a href="update-subjectcombination.php?subjectcombid=<?php echo htmlentities($result->scid);?>" class="btn btn-success btn-xs"><i class="fa fa-check"></i> Activate</a>
                                                                <?php } else {?>
                                                                <a href="update-subjectcombination.php?subjectcombid=<?php echo htmlentities($result->scid);?>" class="btn btn-danger btn-xs"><i class="fa fa-times"></i> Deactivate</a>
                                                                <?php } ?>
                                                                <a href="manage-subjectcombination.php?del=<?php echo htmlentities($result->scid);?>" onclick="return confirm('Do you really want to delete this subject combination?');" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</a>
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