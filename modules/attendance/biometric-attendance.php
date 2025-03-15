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
    <title>Biometric Attendance - Student Result Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f6fa;
        }
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
        .top-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .fingerprint-scanner {
            text-align: center;
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .fingerprint-icon {
            font-size: 100px;
            color: #6c5ce7;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        .scanner-status {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .attendance-log {
            max-height: 400px;
            overflow-y: auto;
        }
        @keyframes pulse {
            0% {
                opacity: 1;
            }
            50% {
                opacity: 0.6;
            }
            100% {
                opacity: 1;
            }
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
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="top-actions">
                    <h2><i class="fas fa-fingerprint me-2"></i>Biometric Attendance</h2>
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
                        <h5 class="mb-0"><i class="fas fa-hand-point-up me-2"></i>Fingerprint Scanner</h5>
                    </div>
                    <div class="card-body">
                        <div class="fingerprint-scanner">
                            <i class="fas fa-fingerprint fingerprint-icon"></i>
                            <div class="scanner-status">Place your finger on the scanner</div>
                            <div class="progress mb-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" id="scanProgress"></div>
                            </div>
                            <button class="btn btn-primary" id="simulateScan">
                                <i class="fas fa-sync-alt me-2"></i>Simulate Scan
                            </button>
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
                                <label for="scannerDevice" class="form-label">Scanner Device</label>
                                <select class="form-select" id="scannerDevice">
                                    <option value="1">Biometric Scanner - USB001</option>
                                    <option value="2">Fingerprint Reader - USB002</option>
                                    <option value="3">Biometric Device - COM1</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="sensitivity" class="form-label">Scanner Sensitivity</label>
                                <input type="range" class="form-range" min="1" max="10" value="7" id="sensitivity">
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="autoVerify" checked>
                                <label class="form-check-label" for="autoVerify">Auto-verify attendance</label>
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
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Attendance Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Today's Attendance</h6>
                                        <h2 class="mb-0">85%</h2>
                                        <div class="progress mt-2">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Weekly Average</h6>
                                        <h2 class="mb-0">78%</h2>
                                        <div class="progress mt-2">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 78%" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
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
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const simulateScanBtn = document.getElementById('simulateScan');
            const scanProgress = document.getElementById('scanProgress');
            const studentInfo = document.getElementById('studentInfo');
            const attendanceLog = document.getElementById('attendanceLog');
            const totalCount = document.getElementById('totalCount');
            const presentCount = document.getElementById('presentCount');
            const absentCount = document.getElementById('absentCount');
            
            // Sample student data
            const students = [
                { id: 'STU001', name: 'John Doe', class: 'Class 10-A', photo: 'https://randomuser.me/api/portraits/men/1.jpg' },
                { id: 'STU002', name: 'Jane Smith', class: 'Class 11-B', photo: 'https://randomuser.me/api/portraits/women/2.jpg' },
                { id: 'STU003', name: 'Mike Johnson', class: 'Class 9-C', photo: 'https://randomuser.me/api/portraits/men/3.jpg' },
                { id: 'STU004', name: 'Sarah Williams', class: 'Class 12-A', photo: 'https://randomuser.me/api/portraits/women/4.jpg' },
                { id: 'STU005', name: 'Robert Brown', class: 'Class 10-B', photo: 'https://randomuser.me/api/portraits/men/5.jpg' }
            ];
            
            // Simulate fingerprint scan
            simulateScanBtn.addEventListener('click', function() {
                // Reset progress
                scanProgress.style.width = '0%';
                studentInfo.style.display = 'none';
                
                // Simulate scanning progress
                let progress = 0;
                const interval = setInterval(function() {
                    progress += 10;
                    scanProgress.style.width = progress + '%';
                    
                    if (progress >= 100) {
                        clearInterval(interval);
                        
                        // Select a random student
                        const randomStudent = students[Math.floor(Math.random() * students.length)];
                        
                        // Update student info
                        document.getElementById('studentName').textContent = randomStudent.name;
                        document.getElementById('studentClass').textContent = 'Class: ' + randomStudent.class;
                        document.getElementById('studentId').textContent = 'ID: ' + randomStudent.id;
                        document.getElementById('studentPhoto').src = randomStudent.photo;
                        
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
                    }
                }, 100);
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