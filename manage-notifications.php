<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Notifications - Student Result Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            padding: 15px 20px;
        }
        .badge {
            font-size: 0.8rem;
            padding: 0.5em 0.8em;
        }
        .btn-action {
            margin-right: 5px;
        }
        .table-responsive {
            padding: 15px;
        }
        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .top-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .page-title {
            font-size: 1.5rem;
            margin: 0;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <!-- Add this new top section with back button -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="top-actions">
                    <h2 class="page-title"><i class="fas fa-bell me-2"></i>Notification Management</h2>
                    <a href="dashboard.php" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
        <!-- Original content continues -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header notification-header">
                        <h4 class="mb-0"><i class="fas fa-bell me-2"></i>Manage Notifications</h4>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNotificationModal">
                            <i class="fas fa-plus me-2"></i>Add New Notification
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="notificationsTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    // Database connection and query setup
                                    require_once('includes/config.php');
                                    
                                    // Check if notifications table exists, if not create it
                                    try {
                                        $checkTable = $dbh->query("SHOW TABLES LIKE 'notifications'");
                                        if($checkTable->rowCount() == 0) {
                                            // Table doesn't exist, create it
                                            $createTable = "CREATE TABLE `notifications` (
                                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                                `type` varchar(50) NOT NULL,
                                                `title` varchar(255) NOT NULL,
                                                `description` text NOT NULL,
                                                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                                PRIMARY KEY (`id`)
                                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
                                            $dbh->exec($createTable);
                                        }
                                    } catch (PDOException $e) {
                                        echo '<div class="alert alert-danger">Error creating table: ' . $e->getMessage() . '</div>';
                                    }
                                    
                                    // Handle form submissions
                                    if(isset($_POST['submit'])) {
                                        $type = $_POST['notification_type'];
                                        $title = $_POST['notification_title'];
                                        $description = $_POST['notification_description'];
                                        
                                        try {
                                            $sql = "INSERT INTO notifications (type, title, description) VALUES (:type, :title, :description)";
                                            $query = $dbh->prepare($sql);
                                            $query->bindParam(':type', $type, PDO::PARAM_STR);
                                            $query->bindParam(':title', $title, PDO::PARAM_STR);
                                            $query->bindParam(':description', $description, PDO::PARAM_STR);
                                            $query->execute();
                                            
                                            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                                    Notification added successfully!
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                  </div>';
                                        } catch (PDOException $e) {
                                            echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
                                        }
                                    }
                                    
                                    if(isset($_POST['update'])) {
                                        $id = $_POST['notification_id'];
                                        $type = $_POST['notification_type'];
                                        $title = $_POST['notification_title'];
                                        $description = $_POST['notification_description'];
                                        
                                        try {
                                            $sql = "UPDATE notifications SET type=:type, title=:title, description=:description WHERE id=:id";
                                            $query = $dbh->prepare($sql);
                                            $query->bindParam(':type', $type, PDO::PARAM_STR);
                                            $query->bindParam(':title', $title, PDO::PARAM_STR);
                                            $query->bindParam(':description', $description, PDO::PARAM_STR);
                                            $query->bindParam(':id', $id, PDO::PARAM_INT);
                                            $query->execute();
                                            
                                            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                                    Notification updated successfully!
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                  </div>';
                                        } catch (PDOException $e) {
                                            echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
                                        }
                                    }
                                    
                                    if(isset($_GET['del'])) {
                                        $id = $_GET['del'];
                                        
                                        try {
                                            $sql = "DELETE FROM notifications WHERE id=:id";
                                            $query = $dbh->prepare($sql);
                                            $query->bindParam(':id', $id, PDO::PARAM_INT);
                                            $query->execute();
                                            
                                            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                                    Notification deleted successfully!
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                  </div>';
                                        } catch (PDOException $e) {
                                            echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
                                        }
                                    }
                                    
                                    // Initialize the query variable
                                    $query = null;
                                    $results = array();
                                    
                                    try {
                                        // Prepare and execute the query to fetch notifications
                                        $sql = "SELECT * FROM notifications ORDER BY created_at DESC";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    } catch (PDOException $e) {
                                        echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
                                    }
                                    
                                    $cnt = 1;
                                    
                                    if($query && $query->rowCount() > 0) {
                                        foreach($results as $result) { 
                                ?>
                                <tr>
                                    <td><?php echo htmlentities($cnt); ?></td>
                                    <td>
                                        <?php 
                                            $type = htmlentities($result->type);
                                            if($type == 'student') {
                                                echo '<span class="badge bg-primary">Student</span>';
                                            } else if($type == 'result') {
                                                echo '<span class="badge bg-success">Result</span>';
                                            } else if($type == 'subject') {
                                                echo '<span class="badge bg-info">Subject</span>';
                                            } else {
                                                echo '<span class="badge bg-secondary">Other</span>';
                                            }
                                        ?>
                                    </td>
                                    <td><?php echo htmlentities($result->title); ?></td>
                                    <td><?php echo htmlentities($result->description); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($result->created_at)); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary btn-action edit-notification" 
                                            data-id="<?php echo htmlentities($result->id); ?>"
                                            data-type="<?php echo htmlentities($result->type); ?>"
                                            data-title="<?php echo htmlentities($result->title); ?>"
                                            data-description="<?php echo htmlentities($result->description); ?>"
                                            data-bs-toggle="modal" data-bs-target="#editNotificationModal">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <a href="manage-notifications.php?del=<?php echo htmlentities($result->id); ?>" 
                                            class="btn btn-sm btn-danger btn-action" 
                                            onclick="return confirm('Are you sure you want to delete this notification?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                        $cnt++; 
                                        }
                                    } else {
                                        echo '<tr><td colspan="6" class="text-center">No notifications found</td></tr>';
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Notification Modal -->
    <div class="modal fade" id="addNotificationModal" tabindex="-1" aria-labelledby="addNotificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="addNotificationModalLabel"><i class="fas fa-plus-circle me-2"></i>Add New Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="notification_type" class="form-label">Notification Type</label>
                            <select class="form-select" id="notification_type" name="notification_type" required>
                                <option value="">Select Type</option>
                                <option value="student">Student</option>
                                <option value="result">Result</option>
                                <option value="subject">Subject</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="notification_title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="notification_title" name="notification_title" required>
                        </div>
                        <div class="mb-3">
                            <label for="notification_description" class="form-label">Description</label>
                            <textarea class="form-control" id="notification_description" name="notification_description" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Add Notification</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Notification Modal -->
    <div class="modal fade" id="editNotificationModal" tabindex="-1" aria-labelledby="editNotificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="editNotificationModalLabel"><i class="fas fa-edit me-2"></i>Edit Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" id="edit_notification_id" name="notification_id">
                        <div class="mb-3">
                            <label for="edit_notification_type" class="form-label">Notification Type</label>
                            <select class="form-select" id="edit_notification_type" name="notification_type" required>
                                <option value="">Select Type</option>
                                <option value="student">Student</option>
                                <option value="result">Result</option>
                                <option value="subject">Subject</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_notification_title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="edit_notification_title" name="notification_title" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_notification_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_notification_description" name="notification_description" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-save me-2"></i>Update Notification</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#notificationsTable').DataTable({
            "order": [[4, "desc"]] // Sort by created date by default
        });
        
        // Edit Notification - Populate Modal with Data
        $('.edit-notification').click(function() {
            var id = $(this).data('id');
            var type = $(this).data('type');
            var title = $(this).data('title');
            var description = $(this).data('description');
            
            $('#edit_notification_id').val(id);
            $('#edit_notification_type').val(type);
            $('#edit_notification_title').val(title);
            $('#edit_notification_description').val(description);
        });
        
        // Sidebar toggle functionality
        $('#sidebarToggle').click(function() {
            $('.sidebar').toggleClass('show');
            $('.main-content').toggleClass('margin-left-0');
        });
        
        // Submenu toggle
        $('.has-submenu > a').click(function(e) {
            e.preventDefault();
            $(this).parent().toggleClass('open');
            $(this).next('.submenu').slideToggle();
        });
    });
</script>
</body>
</html>