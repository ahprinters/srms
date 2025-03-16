<?php
session_start();
error_reporting(0);
include('../../includes/config.php');
if(strlen($_SESSION['alogin'])=="") {   
    header("Location: ../../index.php"); 
} else {
    // Get event ID from URL
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        
        // Fetch event details
        $sql = "SELECT * FROM tblevents WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();
        $event = $query->fetch(PDO::FETCH_OBJ);
    }
    
    // Process form submission
    if(isset($_POST['update'])) {
        $eventTitle = $_POST['eventTitle'];
        $eventDescription = $_POST['eventDescription'];
        $eventDate = $_POST['eventDate'];
        $eventTime = $_POST['eventTime'];
        $eventLocation = $_POST['eventLocation'];
        $eventType = $_POST['eventType'];
        $id = $_POST['id'];
        
        // Update event in database
        $sql = "UPDATE tblevents SET eventTitle=:eventTitle, eventDescription=:eventDescription, eventDate=:eventDate, eventTime=:eventTime, eventLocation=:eventLocation, eventType=:eventType WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':eventTitle', $eventTitle, PDO::PARAM_STR);
        $query->bindParam(':eventDescription', $eventDescription, PDO::PARAM_STR);
        $query->bindParam(':eventDate', $eventDate, PDO::PARAM_STR);
        $query->bindParam(':eventTime', $eventTime, PDO::PARAM_STR);
        $query->bindParam(':eventLocation', $eventLocation, PDO::PARAM_STR);
        $query->bindParam(':eventType', $eventType, PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();
        
        $msg = "Event updated successfully";
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SRMS Admin | Edit Event</title>
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

            <!-- ========== TOP NAVBAR ========== -->
            <?php include('../../includes/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">
                    <?php include('../../includes/sidebar.php');?>
                    <!-- /.left-sidebar -->

                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Edit Event</h2>
                                </div>
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="../../dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li><a href="manage-events.php">Events</a></li>
                                        <li class="active">Edit Event</li>
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
                                                    <h5 class="pull-left">Edit Event Details</h5>
                                                    <div class="pull-right">
                                                        <a href="manage-events.php" class="btn btn-primary btn-sm"><i class="fa fa-list"></i> Manage Events</a>
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
                                                
                                                <form class="form-horizontal" method="post">
                                                    <input type="hidden" name="id" value="<?php echo htmlentities($event->id); ?>">
                                                    <div class="form-group">
                                                        <label for="eventTitle" class="col-sm-2 control-label">Event Title</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="eventTitle" class="form-control" id="eventTitle" value="<?php echo htmlentities($event->eventTitle); ?>" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="eventDescription" class="col-sm-2 control-label">Description</label>
                                                        <div class="col-sm-10">
                                                            <textarea name="eventDescription" class="form-control" id="eventDescription" rows="5" required><?php echo htmlentities($event->eventDescription); ?></textarea>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="eventDate" class="col-sm-2 control-label">Event Date</label>
                                                        <div class="col-sm-10">
                                                            <input type="date" name="eventDate" class="form-control" id="eventDate" value="<?php echo htmlentities($event->eventDate); ?>" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="eventTime" class="col-sm-2 control-label">Event Time</label>
                                                        <div class="col-sm-10">
                                                            <input type="time" name="eventTime" class="form-control" id="eventTime" value="<?php echo htmlentities($event->eventTime); ?>" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="eventLocation" class="col-sm-2 control-label">Location</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="eventLocation" class="form-control" id="eventLocation" value="<?php echo htmlentities($event->eventLocation); ?>" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="eventType" class="col-sm-2 control-label">Event Type</label>
                                                        <div class="col-sm-10">
                                                            <select name="eventType" class="form-control" id="eventType" required>
                                                                <option value="">Select Event Type</option>
                                                                <option value="Academic" <?php if($event->eventType == 'Academic') echo 'selected'; ?>>Academic</option>
                                                                <option value="Cultural" <?php if($event->eventType == 'Cultural') echo 'selected'; ?>>Cultural</option>
                                                                <option value="Sports" <?php if($event->eventType == 'Sports') echo 'selected'; ?>>Sports</option>
                                                                <option value="Holiday" <?php if($event->eventType == 'Holiday') echo 'selected'; ?>>Holiday</option>
                                                                <option value="Examination" <?php if($event->eventType == 'Examination') echo 'selected'; ?>>Examination</option>
                                                                <option value="Other" <?php if($event->eventType == 'Other') echo 'selected'; ?>>Other</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="update" class="btn btn-primary">Update Event</button>
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