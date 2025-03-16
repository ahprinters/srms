<?php
session_start();
error_reporting(0);
include('../../includes/config.php');
if(strlen($_SESSION['alogin'])=="")
{   
    header("Location: ../../index.php"); 
}
else{
    if(isset($_POST['submit']))
    {
        $subjectname=$_POST['subjectname'];
        $subjectcode=$_POST['subjectcode']; 
        $sql="INSERT INTO  tblsubjects(SubjectName,SubjectCode) VALUES(:subjectname,:subjectcode)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':subjectname',$subjectname,PDO::PARAM_STR);
        $query->bindParam(':subjectcode',$subjectcode,PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        if($lastInsertId)
        {
            $msg="Subject Created Successfully";
        }
        else 
        {
            $error="Something went wrong. Please try again";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SRMS Admin | Create Subject</title>
        <link rel="stylesheet" href="../../css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="../../css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="../../css/animate-css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="../../css/lobipanel/lobipanel.min.css" media="screen" >
        <link rel="stylesheet" href="../../css/prism/prism.css" media="screen" >
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
                                    <h2 class="title">Create Subject</h2>
                                </div>
                            </div>
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="../../dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li><a href="manage-subjects.php">Subjects</a></li>
                                        <li class="active">Create Subject</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <section class="section">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2">
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title clearfix">
                                                    <h5 class="pull-left">Create Subject</h5>
                                                    <div class="pull-right">
                                                        <?php echo backToDashboardButton(); ?>
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
                                            <div class="panel-body">
                                                <form method="post">
                                                    <div class="form-group">
                                                        <label for="subjectname">Subject Name</label>
                                                        <input type="text" class="form-control" id="subjectname" name="subjectname" placeholder="Subject Name" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="subjectcode">Subject Code</label>
                                                        <input type="text" class="form-control" id="subjectcode" name="subjectcode" placeholder="Subject Code" required>
                                                    </div>
                                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
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
        <script src="../../js/jquery/jquery-2.2.4.min.js"></script>
        <script src="../../js/bootstrap/bootstrap.min.js"></script>
        <script src="../../js/pace/pace.min.js"></script>
        <script src="../../js/lobipanel/lobipanel.min.js"></script>
        <script src="../../js/iscroll/iscroll.js"></script>
        <script src="../../js/prism/prism.js"></script>
        <script src="../../js/main.js"></script>
    </body>
</html>
<?php } ?>
