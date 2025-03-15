<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="") {   
    header("Location: index.php"); 
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card Attendance - Student Result Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Additional styles specific to this page */
        .scanner-area {
            text-align: center;
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 20px;
            position: relative;
        }
        .scanner-icon {
            font-size: 80px;
            color: #3498db;
            margin-bottom: 20px;
        }
        .scanner-status {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .scanner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(52, 152, 219, 0.1);
            border-radius: 10px;
            display: none;
        }
        .scanner-line {
            position: absolute;
            height: 2px;
            background-color: #3498db;
            width: 100%;
            top: 0;
            left: 0;
            box-shadow: 0 0 8px 2px rgba(52, 152, 219, 0.8);
            animation: scan 2s linear infinite;
        }
        @keyframes scan {
            0% {
                top: 0;
            }
            50% {
                top: 100%;
            }
            100% {
                top: 0;
            }
        }
        .attendance-log {
            max-height: 400px;
            overflow-y: auto;
        }
        .student-info {
            display: none;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .student-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 20px;
        }
        .success-animation {
            animation: successPulse 1s;
        }
        @keyframes successPulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }
        .id-card-preview {
            max-width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <?php include('includes/sidebar.php'); ?>
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Top Navigation -->
            <?php include('includes/topbar.php'); ?>
            
            <!-- Main Content -->
            <div class="container-fluid px-4 py-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="top-actions">
                            <h2><i class="fas fa-id-card me-2"></i>ID Card Attendance</h2>
                            <a href="dashboard.php" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-qrcode me-2"></i>ID Card Scanner</h5>
                            </div>
                            <div class="card-body">
                                <div class="scanner-area" id="scannerArea">
                                    <i class="fas fa-id-card scanner-icon"></i>
                                    <div class="scanner-status">Scan Student ID Card</div>
                                    <div class="mb-3">
                                        <input type="text" class="form-control" id="manualIdInput" placeholder="Or enter student ID manually">
                                    </div>
                                    <button class="btn btn-primary" id="startScan">
                                        <i class="fas fa-camera me-2"></i>Start Camera Scan
                                    </button>
                                    <button class="btn btn-secondary ms-2" id="simulateScan">
                                        <i class="fas fa-sync-alt me-2"></i>Simulate Scan
                                    </button>
                                    <div class="scanner-overlay" id="scannerOverlay">
                                        <div class="scanner-line"></div>
                                    </div>
                                </div>

                                <div class="student-info" id="studentInfo">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="https://via.placeholder.com/100" alt="Student Photo" class="student-photo" id="studentPhoto">
                                        <div>
                                            <h4 id="studentName">Student Name</h4>
                                            <p class="mb-1" id="studentClass">Class: </p>
                                            <p class="mb-1" id="studentId">ID: </p>
                                            <div class="badge bg-success" id="attendanceStatus">Attendance Marked</div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <img src="https://via.placeholder.com/350x200?text=Student+ID+Card" alt="ID Card Preview" class="id-card-preview" id="idCardPreview">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Scanner Settings</h5>
                            </div>
                            <div class="card-body">
                                <form>
                                    <div class="mb-3">
                                        <label for="scannerType" class="form-label">Scanner Type</label>
                                        <select class="form-select" id="scannerType">
                                            <option value="qr">QR Code Scanner</option>
                                            <option value="barcode">Barcode Scanner</option>
                                            <option value="rfid">RFID Reader</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="cameraSource" class="form-label">Camera Source</label>
                                        <select class="form-select" id="cameraSource">
                                            <option value="0">Default Camera</option>
                                            <option value="1">Front Camera</option>
                                            <option value="2">Back Camera</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="autoCapture" checked>
                                        <label class="form-check-label" for="autoCapture">Auto-capture ID cards</label>
                                    </div>
                                    <button type="button" class="btn btn-primary">Save Settings</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Today's Attendance Log</h5>
                                    <div>
                                        <input type="date" class="form-control form-control-sm" id="attendanceDate" value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="attendance-log">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Time</th>
                                                <th>Student ID</th>
                                                <th>Name</th>
                                                <th>Class</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="attendanceLog">
                                            <!-- Attendance logs will be added here dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-primary me-2">Total: <span id="totalCount">0</span></span>
                                        <span class="badge bg-success me-2">Present: <span id="presentCount">0</span></span>
                                        <span class="badge bg-danger">Absent: <span id="absentCount">0</span></span>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary" id="exportBtn">
                                        <i class="fas fa-download me-1"></i> Export
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>How to Use</h5>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="howToUseAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Using the ID Card Scanner
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#howToUseAccordion">
                                            <div class="accordion-body">
                                                <p>1. Click "Start Camera Scan" to activate the camera.</p>
                                                <p>2. Hold the student ID card in front of the camera.</p>
                                                <p>3. The system will automatically detect and scan the QR code or barcode.</p>
                                                <p>4. Alternatively, you can enter the student ID manually in the input field.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwo">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                Manual ID Entry
                                            </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#howToUseAccordion">
                                            <div class="accordion-body">
                                                <p>1. Enter the student ID in the input field.</p>
                                                <p>2. Press Enter or click outside the field to submit.</p>
                                                <p>3. The system will verify the ID and mark attendance if valid.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingThree">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                Troubleshooting
                                            </button>
                                        </h2>
                                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#howToUseAccordion">
                                            <div class="accordion-body">
                                                <p><strong>Scanner not detecting ID card:</strong> Make sure the card is properly aligned and well-lit.</p>
                                                <p><strong>Camera not starting:</strong> Check browser permissions and ensure your device has a working camera.</p>
                                                <p><strong>ID not recognized:</strong> Verify the student ID is registered in the system.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
        // Toggle sidebar
        document.getElementById("sidebarToggle").addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("wrapper").classList.toggle("toggled");
        });
        
        // Submenu toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Get all submenu parent items
            const hasSubmenuItems = document.querySelectorAll('.has-submenu');
            
            // Add click event to each submenu parent
            hasSubmenuItems.forEach(item => {
                const link = item.querySelector('a');
                const submenu = item.querySelector('.submenu');
                
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Toggle the submenu visibility
                    if (submenu.style.display === 'block') {
                        submenu.style.display = 'none';
                        item.classList.remove('active');
                    } else {
                        // Close all other submenus first
                        document.querySelectorAll('.submenu').forEach(menu => {
                            menu.style.display = 'none';
                            menu.parentElement.classList.remove('active');
                        });
                        
                        // Open this submenu
                        submenu.style.display = 'block';
                        item.classList.add('active');
                    }
                });
            });
        });
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startScanBtn = document.getElementById('startScan');
            const simulateScanBtn = document.getElementById('simulateScan');
            const scannerOverlay = document.getElementById('scannerOverlay');
            const manualIdInput = document.getElementById('manualIdInput');
            const studentInfo = document.getElementById('studentInfo');
            const attendanceLog = document.getElementById('attendanceLog');
            const totalCount = document.getElementById('totalCount');
            const presentCount = document.getElementById('presentCount');
            const absentCount = document.getElementById('absentCount');
            
            // Sample student data
            const students = [
                { id: 'STU001', name: 'John Doe', class: 'Class 10-A', photo: 'https://randomuser.me/api/portraits/men/1.jpg', idCard: 'https://via.placeholder.com/350x200/3498db/ffffff?text=ID+Card:+John+Doe' },
                { id: 'STU002', name: 'Jane Smith', class: 'Class 11-B', photo: 'https://randomuser.me/api/portraits/women/2.jpg', idCard: 'https://via.placeholder.com/350x200/e74c3c/ffffff?text=ID+Card:+Jane+Smith' },
                { id: 'STU003', name: 'Mike Johnson', class: 'Class 9-C', photo: 'https://randomuser.me/api/portraits/men/3.jpg', idCard: 'https://via.placeholder.com/350x200/2ecc71/ffffff?text=ID+Card:+Mike+Johnson' },
                { id: 'STU004', name: 'Sarah Williams', class: 'Class 12-A', photo: 'https://randomuser.me/api/portraits/women/4.jpg', idCard: 'https://via.placeholder.com/350x200/f39c12/ffffff?text=ID+Card:+Sarah+Williams' },
                { id: 'STU005', name: 'Robert Brown', class: 'Class 10-B', photo: 'https://randomuser.me/api/portraits/men/5.jpg', idCard: 'https://via.placeholder.com/350x200/9b59b6/ffffff?text=ID+Card:+Robert+Brown' }
            ];
            
            // Start camera scan
            startScanBtn.addEventListener('click', function() {
                scannerOverlay.style.display = 'block';
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Scanning...';
                
                // Simulate camera access and scanning
                setTimeout(() => {
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-camera me-2"></i>Start Camera Scan';
                    scannerOverlay.style.display = 'none';
                    
                    // Show alert about camera functionality
                    alert('Camera functionality would be implemented with a QR/barcode scanning library like Instascan or QuaggaJS in a production environment.');
                }, 3000);
            });
            
            // Simulate ID card scan
            simulateScanBtn.addEventListener('click', function() {
                scannerOverlay.style.display = 'block';
                
                // Simulate scanning process
                setTimeout(() => {
                    scannerOverlay.style.display = 'none';
                    
                    // Select a random student
                    const randomStudent = students[Math.floor(Math.random() * students.length)];
                    
                    // Update student info
                    document.getElementById('studentName').textContent = randomStudent.name;
                    document.getElementById('studentClass').textContent = 'Class: ' + randomStudent.class;
                    document.getElementById('studentId').textContent = 'ID: ' + randomStudent.id;
                    document.getElementById('studentPhoto').src = randomStudent.photo;
                    document.getElementById('idCardPreview').src = randomStudent.idCard;
                    
                    // Show student info
                    studentInfo.style.display = 'block';
                    studentInfo.classList.add('success-animation');
                    
                    // Add to attendance log
                    const now = new Date();
                    const timeString = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td>${timeString}</td>
                        <td>${randomStudent.id}</td>
                        <td>${randomStudent.name}</td>
                        <td>${randomStudent.class}</td>
                        <td><span class="badge bg-success">Present</span></td>
                    `;
                    
                    // Add to the top of the table
                    if (attendanceLog.firstChild) {
                        attendanceLog.insertBefore(newRow, attendanceLog.firstChild);
                    } else {
                        attendanceLog.appendChild(newRow);
                    }
                    
                    // Update counters
                    updateCounters();
                    
                    // Play success sound
                    const audio = new Audio('https://assets.mixkit.co/sfx/preview/mixkit-correct-answer-tone-2870.mp3');
                    audio.play();
                }, 2000);
            });
            
            // Manual ID input
            manualIdInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const studentId = this.value.trim().toUpperCase();
                    
                    if (studentId) {
                        // Find student by ID
                        const student = students.find(s => s.id === studentId);
                        
                        if (student) {
                            // Update student info
                            document.getElementById('studentName').textContent = student.name;
                            document.getElementById('studentClass').textContent = 'Class: ' + student.class;
                            document.getElementById('studentId').textContent = 'ID: ' + student.id;
                            document.getElementById('studentPhoto').src = student.photo;
                            document.getElementById('idCardPreview').src = student.idCard;
                            
                            // Show student info
                            studentInfo.style.display = 'block';
                            studentInfo.classList.add('success-animation');
                            
                            // Add to attendance log
                            const now = new Date();
                            const timeString = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                            
                            const newRow = document.createElement('tr');
                            newRow.innerHTML = `
                                <td>${timeString}</td>
                                <td>${student.id}</td>
                                <td>${student.name}</td>
                                <td>${student.class}</td>
                                <td><span class="badge bg-success">Present</span></td>
                            `;
                            
                            // Add to the top of the table
                            if (attendanceLog.firstChild) {
                                attendanceLog.insertBefore(newRow, attendanceLog.firstChild);
                            } else {
                                attendanceLog.appendChild(newRow);
                            }
                            
                            // Update counters
                            updateCounters();
                            
                            // Play success sound
                            const audio = new Audio('https://assets.mixkit.co/sfx/preview/mixkit-correct-answer-tone-2870.mp3');
                            audio.play();
                            
                            // Clear input
                            this.value = '';
                        } else {
                            alert('Student ID not found. Please check and try again.');
                        }
                    }
                }
            });
            
            // Function to update counters
            function updateCounters() {
                const rows = attendanceLog.getElementsByTagName('tr');
                totalCount.textContent = rows.length;
                
                let present = 0;
                for (let i = 0; i < rows.length; i++) {
                    if (rows[i].querySelector('.badge-success') || rows[i].querySelector('.bg-success')) {
                        present++;
                    }
                }
                
                presentCount.textContent = present;
                absentCount.textContent = rows.length - present;
            }
            
            // Export button functionality
            document.getElementById('exportBtn').addEventListener('click', function() {
                const date = document.getElementById('attendanceDate').value;
                alert(`Attendance data for ${date} exported successfully!`);
            });
            
            // Initialize with some sample data
            const sampleData = [
                { time: '08:15', id: 'STU003', name: 'Mike Johnson', class: 'Class 9-C', status: 'Present' },
                { time: '08:10', id: 'STU001', name: 'John Doe', class: 'Class 10-A', status: 'Present' },
                { time: '08:05', id: 'STU004', name: 'Sarah Williams', class: 'Class 12-A', status: 'Present' }
            ];
            
            sampleData.forEach(student => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${student.time}</td>
                    <td>${student.id}</td>
                    <td>${student.name}</td>
                    <td>${student.class}</td>
                    <td><span class="badge bg-success">${student.status}</span></td>
                `;
                attendanceLog.appendChild(row);
            });
            
            // Update initial counters
            updateCounters();
        });
    </script>
</body>
</html>
<?php
}
?>