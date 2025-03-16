<?php
session_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../../includes/config.php');
if(strlen($_SESSION['alogin'])=="") {   
    header("Location: ../../index.php"); 
} else {
    // Initialize variables to avoid undefined variable warnings
    $msg = "";
    $error = "";
    
    // Process form submission
    if(isset($_POST['submit'])) {
        $eventTitle = $_POST['eventTitle'];
        $eventDescription = $_POST['eventDescription'];
        $eventDate = $_POST['eventDate'];
        $eventTime = $_POST['eventTime'];
        $eventLocation = $_POST['eventLocation'];
        $eventType = $_POST['eventType'];
        
        // Inside your try block, add this debugging line
        try {
            // For debugging - print the values being inserted
            echo "<pre>Inserting: " . 
                 "Title: $eventTitle, " . 
                 "Description: $eventDescription, " . 
                 "Date: $eventDate, " . 
                 "Time: $eventTime, " . 
                 "Location: $eventLocation, " . 
                 "Type: $eventType, " . 
                 "Created By: " . $_SESSION['alogin'] . 
                 "</pre>";
            
            // Insert event into database - using the correct column names that match the table structure
            $sql = "INSERT INTO tblevents(eventTitle, eventDescription, eventDate, eventTime, eventLocation, eventType, createdBy) 
                    VALUES(:eventTitle, :eventDescription, :eventDate, :eventTime, :eventLocation, :eventType, :createdBy)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':eventTitle', $eventTitle, PDO::PARAM_STR);
            $query->bindParam(':eventDescription', $eventDescription, PDO::PARAM_STR);
            $query->bindParam(':eventDate', $eventDate, PDO::PARAM_STR);
            $query->bindParam(':eventTime', $eventTime, PDO::PARAM_STR);
            $query->bindParam(':eventLocation', $eventLocation, PDO::PARAM_STR);
            $query->bindParam(':eventType', $eventType, PDO::PARAM_STR);
            $query->bindParam(':createdBy', $_SESSION['alogin'], PDO::PARAM_STR);
            $result = $query->execute();
            
            if($result) {
                $lastInsertId = $dbh->lastInsertId();
                $msg = "Event added successfully (ID: $lastInsertId)";
            } else {
                $error = "Database error: " . implode(", ", $query->errorInfo());
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SRMS Admin | Add Event</title>
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
                                    <h2 class="title">Add New Event</h2>
                                </div>
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="../../dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li><a href="manage-events.php">Events</a></li>
                                        <li class="active">Add Event</li>
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
                                                    <h5 class="pull-left">Add New Event</h5>
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
                                                <?php if($error){ ?>
                                                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                    <strong>Error!</strong> <?php echo htmlentities($error); ?>
                                                </div>
                                                <?php } ?>
                                                
                                                <form class="form-horizontal" method="post">
                                                    <div class="form-group">
                                                        <label for="eventTitle" class="col-sm-2 control-label">Event Title</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="eventTitle" class="form-control" id="eventTitle" placeholder="Enter Event Title" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="eventDescription" class="col-sm-2 control-label">Description</label>
                                                        <div class="col-sm-10">
                                                            <textarea name="eventDescription" class="form-control" id="eventDescription" rows="5" placeholder="Enter Event Description" required></textarea>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="eventDate" class="col-sm-2 control-label">Event Date</label>
                                                        <div class="col-sm-10">
                                                            <input type="date" name="eventDate" class="form-control" id="eventDate" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="eventTime" class="col-sm-2 control-label">Event Time</label>
                                                        <div class="col-sm-10">
                                                            <input type="time" name="eventTime" class="form-control" id="eventTime" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="eventLocation" class="col-sm-2 control-label">Location</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="eventLocation" class="form-control" id="eventLocation" placeholder="Enter Event Location" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="eventType" class="col-sm-2 control-label">Event Type</label>
                                                        <div class="col-sm-10">
                                                            <select name="eventType" class="form-control" id="eventType" required>
                                                                <option value="">Select Event Type</option>
                                                                <option value="Academic">Academic</option>
                                                                <option value="Cultural">Cultural</option>
                                                                <option value="Sports">Sports</option>
                                                                <option value="Holiday">Holiday</option>
                                                                <option value="Examination">Examination</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="submit" class="btn btn-primary">Add Event</button>
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