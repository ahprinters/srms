<?php
session_start();
error_reporting(0);
include('../../includes/config.php');
if(strlen($_SESSION['alogin'])=="") {   
    header("Location: ../../index.php"); 
} else {
    // Delete event if requested
    if(isset($_GET['del'])) {
        $id = $_GET['del'];
        $sql = "DELETE FROM tblevents WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();
        $msg = "Event deleted successfully";
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SRMS Admin | Manage Events</title>
        <link rel="stylesheet" href="../../css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="../../css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="../../css/animate-css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="../../css/lobipanel/lobipanel.min.css" media="screen" >
        <link rel="stylesheet" href="../../css/prism/prism.css" media="screen" >
        <link rel="stylesheet" href="../../css/datatables/datatables.min.css" media="screen" >
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
                                    <h2 class="title">Manage Events</h2>
                                </div>
                                <!-- /.col-md-6 -->
                                <!-- Add this button next to the "Add Event" button in the page-title-div section -->
                                <div class="col-md-6 text-right">
                                    <a href="event-calendar.php" class="btn btn-info btn-sm"><i class="fa fa-calendar"></i> Calendar View</a>
                                    <a href="add-event.php" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Event</a>
                                </div>
                                <!-- /.col-md-6 -->
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="../../dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li>Events</li>
                                        <li class="active">Manage Events</li>
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
                                                    <h5 class="pull-left">Event List</h5>
                                                    <div class="pull-right">
                                                        <a href="../../dashboard.php" class="btn btn-success btn-sm"><i class="fa fa-home"></i> Dashboard</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <?php if($msg){ ?>
                                                <div class="alert alert-success alert-dismissible fade in" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                    <strong>Success!</strong> <?php echo htmlentities($msg); ?>
                                                </div>
                                                <?php } ?>

                                                <div class="table-responsive">
                                                    <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Event Title</th>
                                                                <th>Event Type</th>
                                                                <th>Date</th>
                                                                <th>Time</th>
                                                                <th>Location</th>
                                                                <th>Created By</th>
                                                                <th>Created At</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                            $sql = "SELECT * FROM tblevents";
                                                            $query = $dbh->prepare($sql);
                                                            $query->execute();
                                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                            $cnt = 1;
                                                            if($query->rowCount() > 0) {
                                                                foreach($results as $result) { ?>
                                                                <tr>
                                                                    <td><?php echo htmlentities($cnt);?></td>
                                                                    <td><?php echo htmlentities($result->eventTitle);?></td>
                                                                    <td><?php echo htmlentities($result->eventType);?></td>
                                                                    <td><?php echo htmlentities($result->eventDate);?></td>
                                                                    <td><?php echo htmlentities($result->eventTime);?></td>
                                                                    <td><?php echo htmlentities($result->eventLocation);?></td>
                                                                    <td><?php echo htmlentities($result->createdBy);?></td>
                                                                    <td><?php echo htmlentities($result->createdAt);?></td>
                                                                    <td>
                                                                        <a href="edit-event.php?id=<?php echo htmlentities($result->id);?>" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                                                        <a href="manage-events.php?del=<?php echo htmlentities($result->id);?>" onclick="return confirm('Do you want to delete this event?');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</a>
                                                                    </td>
                                                                </tr>
                                                                <?php $cnt++; }
                                                            } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
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
        <script src="../../js/DataTables/datatables.min.js"></script>
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