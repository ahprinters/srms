<?php
session_start();
error_reporting(0);
include('../includes/config.php');
if(strlen($_SESSION['alogin'])=="")
{   
    header("Location: ../index.php"); 
}
else{
    if(isset($_POST['update']))
    {
        $noticeTitle=$_POST['noticetitle'];
        $noticeDetails=$_POST['noticedetails'];
        $nid=intval($_GET['nid']);
        $sql="UPDATE tblnotice SET noticeTitle=:noticeTitle, noticeDetails=:noticeDetails WHERE id=:nid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':noticeTitle',$noticeTitle,PDO::PARAM_STR);
        $query->bindParam(':noticeDetails',$noticeDetails,PDO::PARAM_STR);
        $query->bindParam(':nid',$nid,PDO::PARAM_STR);
        $query->execute();
        $msg="Notification updated successfully";
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SRMS Admin | Edit Notification</title>
        <link rel="stylesheet" href="../css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="../css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="../css/animate-css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="../css/lobipanel/lobipanel.min.css" media="screen" >
        <link rel="stylesheet" href="../css/prism/prism.css" media="screen" >
        <link rel="stylesheet" href="../css/select2/select2.min.css" >
        <link rel="stylesheet" href="../css/main.css" media="screen" >
        <script src="../js/modernizr/modernizr.min.js"></script>
    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">
            <?php include('../includes/topbar.php');?>
            <div class="content-wrapper">
                <div class="content-container">
                    <?php include('../includes/leftbar.php');?>
                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Edit Notification</h2>
                                </div>
                            </div>
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li><a href="manage-notifications.php">Notifications</a></li>
                                        <li class="active">Edit Notification</li>
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
                                                    <h5>Edit Notification</h5>
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
                                                <?php 
                                                $nid=intval($_GET['nid']);
                                                $sql = "SELECT * from tblnotice where id=:nid";
                                                $query = $dbh->prepare($sql);
                                                $query->bindParam(':nid',$nid,PDO::PARAM_STR);
                                                $query->execute();
                                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                                if($query->rowCount() > 0)
                                                {
                                                foreach($results as $result)
                                                {   ?>
                                                <form class="form-horizontal" method="post">
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Notification Title</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="noticetitle" class="form-control" id="noticetitle" value="<?php echo htmlentities($result->noticeTitle);?>" required="required">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Notification Details</label>
                                                        <div class="col-sm-10">
                                                            <textarea name="noticedetails" class="form-control" id="noticedetails" rows="5" required="required"><?php echo htmlentities($result->noticeDetails);?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="update" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </div>
                                                </form>
                                                <?php }} ?>
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
        <script src="../js/jquery/jquery-2.2.4.min.js"></script>
        <script src="../js/bootstrap/bootstrap.min.js"></script>
        <script src="../js/pace/pace.min.js"></script>
        <script src="../js/lobipanel/lobipanel.min.js"></script>
        <script src="../js/iscroll/iscroll.js"></script>
        <script src="../js/prism/prism.js"></script>
        <script src="../js/select2/select2.min.js"></script>
        <script src="../js/main.js"></script>
    </body>
</html>
<?php } ?>