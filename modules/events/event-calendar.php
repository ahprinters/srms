<?php
session_start();
error_reporting(0);
include('../../includes/config.php');
if(strlen($_SESSION['alogin'])=="") {   
    header("Location: ../../index.php"); 
} else {
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SRMS Admin | Event Calendar</title>
        <link rel="stylesheet" href="../../css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="../../css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="../../css/animate-css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="../../css/lobipanel/lobipanel.min.css" media="screen" >
        <link rel="stylesheet" href="../../css/prism/prism.css" media="screen" >
        <link rel="stylesheet" href="../../css/main.css" media="screen" >
        <!-- FullCalendar CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
        <script src="../../js/modernizr/modernizr.min.js"></script>
        <style>
            #calendar {
                max-width: 900px;
                margin: 0 auto;
            }
            .fc-event {
                cursor: pointer;
            }
            .event-details {
                display: none;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: white;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0,0,0,0.3);
                z-index: 1000;
                max-width: 500px;
                width: 90%;
            }
            .event-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }
            .close-event {
                position: absolute;
                top: 10px;
                right: 10px;
                cursor: pointer;
            }
        </style>
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
                                    <h2 class="title">Event Calendar</h2>
                                </div>
                                <!-- /.col-md-6 -->
                                <div class="col-md-6 text-right">
                                    <a href="add-event.php" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Event</a>
                                </div>
                                <!-- /.col-md-6 -->
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="../../dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li><a href="manage-events.php">Events</a></li>
                                        <li class="active">Event Calendar</li>
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
                                                    <h5 class="pull-left">Event Calendar</h5>
                                                    <div class="pull-right">
                                                        <a href="manage-events.php" class="btn btn-primary btn-sm"><i class="fa fa-list"></i> Manage Events</a>
                                                        <a href="../../dashboard.php" class="btn btn-success btn-sm"><i class="fa fa-home"></i> Dashboard</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <div id="calendar"></div>
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

        <!-- Event Details Modal -->
        <div class="event-overlay"></div>
        <div class="event-details">
            <span class="close-event"><i class="fa fa-times"></i></span>
            <h3 id="event-title"></h3>
            <p><strong>Date:</strong> <span id="event-date"></span></p>
            <p><strong>Time:</strong> <span id="event-time"></span></p>
            <p><strong>Location:</strong> <span id="event-location"></span></p>
            <p><strong>Type:</strong> <span id="event-type"></span></p>
            <div id="event-description"></div>
        </div>

        <!-- jQuery -->
        <script src="../../js/jquery/jquery-2.2.4.min.js"></script>
        <script src="../../js/bootstrap/bootstrap.min.js"></script>
        <script src="../../js/pace/pace.min.js"></script>
        <script src="../../js/lobipanel/lobipanel.min.js"></script>
        <script src="../../js/iscroll/iscroll.js"></script>
        <script src="../../js/prism/prism.js"></script>
        <script src="../../js/main.js"></script>
        <!-- FullCalendar JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize calendar
                $('#calendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay,listMonth'
                    },
                    navLinks: true,
                    editable: false,
                    eventLimit: true,
                    events: function(start, end, timezone, callback) {
                        $.ajax({
                            url: 'get-events.php',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                start: start.format('YYYY-MM-DD'),
                                end: end.format('YYYY-MM-DD')
                            },
                            success: function(response) {
                                var events = [];
                                
                                if (response.success) {
                                    $.each(response.data, function(i, item) {
                                        var eventColor;
                                        
                                        // Set color based on event type
                                        switch(item.eventType) {
                                            case 'Academic':
                                                eventColor = '#3498db'; // Blue
                                                break;
                                            case 'Cultural':
                                                eventColor = '#9b59b6'; // Purple
                                                break;
                                            case 'Sports':
                                                eventColor = '#2ecc71'; // Green
                                                break;
                                            case 'Holiday':
                                                eventColor = '#e74c3c'; // Red
                                                break;
                                            case 'Examination':
                                                eventColor = '#f39c12'; // Orange
                                                break;
                                            default:
                                                eventColor = '#95a5a6'; // Gray
                                        }
                                        
                                        events.push({
                                            id: item.id,
                                            title: item.eventTitle,
                                            start: item.eventDate + 'T' + item.eventTime,
                                            description: item.eventDescription,
                                            location: item.eventLocation,
                                            type: item.eventType,
                                            color: eventColor
                                        });
                                    });
                                }
                                
                                callback(events);
                            }
                        });
                    },
                    eventClick: function(calEvent, jsEvent, view) {
                        // Show event details
                        $('#event-title').text(calEvent.title);
                        $('#event-date').text(moment(calEvent.start).format('MMMM D, YYYY'));
                        $('#event-time').text(moment(calEvent.start).format('h:mm A'));
                        $('#event-location').text(calEvent.location);
                        $('#event-type').text(calEvent.type);
                        $('#event-description').html(calEvent.description);
                        
                        $('.event-overlay, .event-details').fadeIn();
                    }
                });
                
                // Close event details
                $('.close-event, .event-overlay').on('click', function() {
                    $('.event-overlay, .event-details').fadeOut();
                });
            });
        </script>
    </body>
</html>
<?php } ?>