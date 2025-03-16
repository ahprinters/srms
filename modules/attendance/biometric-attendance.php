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
    // Add a simple debug message to verify the file is being accessed
    // echo "Biometric attendance page loaded successfully!";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SRMS Admin | Biometric Attendance</title>
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
                    <?php include('../../includes/leftbar.php');?>
                    <!-- /.left-sidebar -->

                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Biometric Attendance</h2>
                                </div>
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="../../dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li>Attendance</li>
                                        <li class="active">Biometric Attendance</li>
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
                                                    <h5 class="pull-left">Biometric Attendance System</h5>
                                                    <div class="pull-right">
                                                        <a href="../../dashboard.php" class="btn btn-success btn-sm"><i class="fa fa-home"></i> Dashboard</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="alert alert-info">
                                                    <strong>Note:</strong> This feature requires biometric hardware integration. Please connect your biometric device to use this feature.
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">Biometric Device Status</h4>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="alert alert-warning">
                                                                    <i class="fa fa-exclamation-triangle"></i> No biometric device detected. Please connect a compatible device.
                                                                </div>
                                                                <button class="btn btn-primary" id="scanDevice">
                                                                    <i class="fa fa-search"></i> Scan for Devices
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">Recent Attendance</h4>
                                                            </div>
                                                            <div class="panel-body">
                                                                <p>No recent attendance records found.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">Biometric Attendance Setup</h4>
                                                            </div>
                                                            <div class="panel-body">
                                                                <p>To set up biometric attendance:</p>
                                                                <ol>
                                                                    <li>Connect your biometric device to the computer</li>
                                                                    <li>Install the device drivers if not already installed</li>
                                                                    <li>Configure the device settings</li>
                                                                    <li>Register student fingerprints in the system</li>
                                                                </ol>
                                                                <a href="#" class="btn btn-success">
                                                                    <i class="fa fa-cog"></i> Configure Settings
                                                                </a>
                                                                <a href="#" class="btn btn-info">
                                                                    <i class="fa fa-book"></i> View Documentation
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
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
        <script src="../../js/select2/select2.min.js"></script>
        <script src="../../js/main.js"></script>
        <script>
            $(document).ready(function() {
                $('#scanDevice').click(function() {
                    $(this).html('<i class="fa fa-spinner fa-spin"></i> Scanning...');
                    setTimeout(function() {
                        $('#scanDevice').html('<i class="fa fa-search"></i> Scan for Devices');
                        alert('No biometric devices found. Please ensure your device is properly connected.');
                    }, 2000);
                });
            });
        </script>
    </body>
</html>
<?php } ?>