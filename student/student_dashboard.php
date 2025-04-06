<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db_connect.php';

// Ensure user ID is set in the session
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in!");
}

$student_id = $_SESSION['user_id'];

// Fetch student data
$sql = "SELECT * FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Handle the case where no student data is found
if (!$student) {
    $student = [
        'student_id' => 'N/A',
        'name' => 'N/A',
        'email' => 'N/A',
        'phone' => 'N/A',
        'course' => 'N/A',
        'profile_picture' => 'default.png' // Default profile picture
    ];
}

// Set profile picture path
$profile_picture = !empty($student['profile_picture']) ? '../assets/uploads/' . $student['profile_picture'] : '../assets/uploads/images.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | Student Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/student_style.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture">
                <h2><?php echo htmlspecialchars($student['name']); ?></h2>
                <p><?php echo htmlspecialchars($student['student_id']); ?></p>
            </div>
            
            <ul class="sidebar-menu">
                <li><a href="student_dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="courses.html"><i class="fas fa-book"></i> My Courses</a></li>
                <li><a href="assignments.html"><i class="fas fa-tasks"></i> Assignments</a></li>
                <li><a href="grades.html"><i class="fas fa-chart-line"></i> Grades</a></li>
                <li><a href="schedule.html"><i class="fas fa-calendar-alt"></i> Schedule</a></li>
                <li><a href="attendance.html"><i class="fas fa-clipboard-check"></i> Attendance</a></li>
                <li><a href="resources.html"><i class="fas fa-file-alt"></i> Resources</a></li>
                <li><a href="update_profile.php"><i class="fas fa-user-edit"></i> Profile Settings</a></li>
                <li><a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Welcome Banner -->
            <div class="welcome-banner">
                <div class="welcome-text">
                    <h1>Welcome <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
                    <p>Computer Science - 3rd Semester</p>
                </div>
                <div class="date-time">
                    <div class="date" id="current-date">March 1, 2025</div>
                    <div class="time" id="current-time">12:00 PM</div>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="dashboard-grid">
                <div class="stat-card">
                    <div class="card-header">
                        <h3>Attendance Rate</h3>
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="card-value">85%</div>
                    <div class="progress-container">
                        <div class="progress-bar" style="width: 85%"></div>
                    </div>
                    <p class="card-info">Overall attendance this semester</p>
                </div>
                
                <div class="stat-card">
                    <div class="card-header">
                        <h3>Upcoming Assignments</h3>
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="card-value">3</div>
                    <p class="card-info">Due within the next 7 days</p>
                </div>
                
                <div class="stat-card">
                    <div class="card-header">
                        <h3>Current GPA</h3>
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="card-value">3.5</div>
                    <p class="card-info">Based on completed courses</p>
                </div>
            </div>
            
            <!-- Announcements Section -->
            <div class="content-section">
                <div class="section-header">
                    <h2>Announcements</h2>
                    <a href="announcements.html" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="announcements-list">
                    <div class="announcement-item">
                        <h4>Spring Break Schedule</h4>
                        <div class="announcement-date">
                            <i class="far fa-calendar"></i> February 28, 2025
                        </div>
                        <p>Please note that the university will be closed for Spring Break from March 15 to March 22, 2025. All facilities will reopen on March 23.</p>
                    </div>
                    <div class="announcement-item">
                        <h4>Midterm Examination Schedule</h4>
                        <div class="announcement-date">
                            <i class="far fa-calendar"></i> February 25, 2025
                        </div>
                        <p>The midterm examination schedule has been posted. Please check your course pages for specific dates and times.</p>
                    </div>
                    <div class="announcement-item">
                        <h4>Career Fair Announcement</h4>
                        <div class="announcement-date">
                            <i class="far fa-calendar"></i> February 20, 2025
                        </div>
                        <p>The Spring Career Fair will be held on March 5, 2025, from 10 AM to 4 PM in the University Center.</p>
                    </div>
                </div>
            </div>
            
            <!-- Two Column Layout for Assignments and Grades -->
            <div class="dashboard-grid">
                <!-- Assignments Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2>Upcoming Assignments</h2>
                        <a href="assignments.html" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
                    </div>
                    
                    <div class="assignment-item">
                        <div class="assignment-details">
                            <h4>Database Design Project</h4>
                            <p>CS305</p>
                        </div>
                        <div class="assignment-due">
                            <div class="date">Mar 3, 2025</div>
                            <div class="status status-due-soon">2 days left</div>
                        </div>
                    </div>
                    <div class="assignment-item">
                        <div class="assignment-details">
                            <h4>Software Engineering Case Study</h4>
                            <p>CS401</p>
                        </div>
                        <div class="assignment-due">
                            <div class="date">Mar 5, 2025</div>
                            <div class="status status-upcoming">4 days left</div>
                        </div>
                    </div>
                    <div class="assignment-item">
                        <div class="assignment-details">
                            <h4>Web Application Development</h4>
                            <p>CS302</p>
                        </div>
                        <div class="assignment-due">
                            <div class="date">Mar 7, 2025</div>
                            <div class="status status-upcoming">6 days left</div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Grades Section -->
                <div class="content-section">
                    <div class="section-header">
                        <h2>Recent Grades</h2>
                        <a href="grades.html" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
                    </div>
                    
                    <table class="grades-table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Assignment</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Database Systems</td>
                                <td>ER Diagram Assignment</td>
                                <td class="grade-a">92%</td>
                            </tr>
                            <tr>
                                <td>Web Development</td>
                                <td>Frontend Project</td>
                                <td class="grade-b">88%</td>
                            </tr>
                            <tr>
                                <td>Software Engineering</td>
                                <td>Requirements Specification</td>
                                <td class="grade-a">95%</td>
                            </tr>
                            <tr>
                                <td>Data Structures</td>
                                <td>Algorithm Analysis</td>
                                <td class="grade-c">79%</td>
                            </tr>
                            <tr>
                                <td>Computer Networks</td>
                                <td>Network Design Quiz</td>
                                <td class="grade-b">85%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Profile Information -->
            <div class="content-section">
                <div class="section-header">
                    <h2>Personal Information</h2>
                    <a href="update_profile.php" class="view-all">Edit <i class="fas fa-edit"></i></a>
                </div>
                
                <div class="profile-section">
                    <div>
                        <div class="profile-field">
                            <label>Student ID</label>
                            <div class="value"><?php echo htmlspecialchars($student['student_id']); ?></div>
                        </div>
                        <div class="profile-field">
                            <label>Full Name</label>
                            <div class="value"><?php echo htmlspecialchars($student['name']); ?></div>
                        </div>
                        <div class="profile-field">
                            <label>Email</label>
                            <div class="value"><?php echo htmlspecialchars($student['email']); ?></div>
                        </div>
                        <div class="profile-field">
                            <label>Phone</label>
                            <div class="value"><?php echo htmlspecialchars($student['phone'] ?: 'N/A'); ?></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="profile-field">
                            <label>Program</label>
                            <div class="value">Computer Science</div>
                        </div>
                        <div class="profile-field">
                            <label>Current Semester</label>
                            <div class="value">3rd</div>
                        </div>
                        <div class="profile-field">
                            <label>Enrollment Date</label>
                            <div class="value">September 1, 2023</div>
                        </div>
                        <div class="profile-field">
                            <label>Current Course</label>
                            <div class="value"><?php echo htmlspecialchars($student['course'] ?: 'N/A'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Calendar Section -->
            <div class="content-section">
                <div class="section-header">
                    <h2>Calendar</h2>
                    <a href="schedule.html" class="view-all">Full Schedule <i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="calendar-wrapper">
                    <div class="calendar-header">
                        <h3>March 2025</h3>
                        <div class="calendar-controls">
                            <button><i class="fas fa-chevron-left"></i></button>
                            <button><i class="fas fa-chevron-right"></i></button>
                        </div>
                    </div>
                    
                    <div class="calendar-grid">
                        <!-- Day names -->
                        <div class="calendar-day day-name">Sun</div>
                        <div class="calendar-day day-name">Mon</div>
                        <div class="calendar-day day-name">Tue</div>
                        <div class="calendar-day day-name">Wed</div>
                        <div class="calendar-day day-name">Thu</div>
                        <div class="calendar-day day-name">Fri</div>
                        <div class="calendar-day day-name">Sat</div>
                        
                        <!-- February days -->
                        <div class="calendar-day other-month">23</div>
                        <div class="calendar-day other-month">24</div>
                        <div class="calendar-day other-month">25</div>
                        <div class="calendar-day other-month">26</div>
                        <div class="calendar-day other-month">27</div>
                        <div class="calendar-day other-month">28</div>
                        
                        <!-- March days -->
                        <div class="calendar-day current-month today">1</div>
                        <div class="calendar-day current-month">2</div>
                        <div class="calendar-day current-month has-event">3</div>
                        <div class="calendar-day current-month">4</div>
                        <div class="calendar-day current-month has-event">5</div>
                        <div class="calendar-day current-month">6</div>
                        <div class="calendar-day current-month">7</div>
                        <div class="calendar-day current-month">8</div>
                        <div class="calendar-day current-month">9</div>
                        <div class="calendar-day current-month has-event">10</div>
                        <div class="calendar-day current-month">11</div>
                        <div class="calendar-day current-month">12</div>
                        <div class="calendar-day current-month">13</div>
                        <div class="calendar-day current-month has-event">14</div>
                        <div class="calendar-day current-month">15</div>
                        <div class="calendar-day current-month">16</div>
                        <div class="calendar-day current-month">17</div>
                        <div class="calendar-day current-month has-event">18</div>
                        <div class="calendar-day current-month">19</div>
                        <div class="calendar-day current-month">20</div>
                        <div class="calendar-day current-month">21</div>
                        <div class="calendar-day current-month">22</div>
                        <div class="calendar-day current-month">23</div>
                        <div class="calendar-day current-month has-event">24</div>
                        <div class="calendar-day current-month">25</div>
                        <div class="calendar-day current-month">26</div>
                        <div class="calendar-day current-month">27</div>
                        <div class="calendar-day current-month">28</div>
                        <div class="calendar-day current-month">29</div>
                        <div class="calendar-day current-month">30</div>
                        <div class="calendar-day current-month">31</div>
                        
                        <!-- April days -->
                        <div class="calendar-day other-month">1</div>
                        <div class="calendar-day other-month">2</div>
                        <div class="calendar-day other-month">3</div>
                        <div class="calendar-day other-month">4</div>
                        <div class="calendar-day other-month">5</div>
                    </div>
                    
                    <div class="events-list">
                        <h4>Today's Schedule</h4>
                        <div class="event-item">
                            <div class="event-time">9:00 AM - 10:30 AM</div>
                            <div class="event-details">
                                <h5>Database Systems</h5>
                                <p>Room 302, Building B</p>
                            </div>
                        </div>
                        <div class="event-item">
                            <div class="event-time">11:00 AM - 12:30 PM</div>
                            <div class="event-details">
                                <h5>Software Engineering</h5>
                                <p>Room 405, Building A</p>
                            </div>
                        </div>
                        <div class="event-item">
                            <div class="event-time">2:00 PM - 3:30 PM</div>
                            <div class="event-details">
                                <h5>Study Group - Final Project</h5>
                                <p>Library, Room 101</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Upcoming Exams Section -->
            <div class="content-section">
                <div class="section-header">
                    <h2>Upcoming Exams</h2>
                    <a href="exams.html" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="exams-list">
                    <div class="exam-item">
                        <div class="exam-details">
                            <h4>Midterm Examination</h4>
                            <p>Software Engineering (CS401)</p>
                        </div>
                        <div class="exam-date">
                            <div class="date">Mar 10, 2025</div>
                            <div class="time">10:00 AM - 12:00 PM</div>
                            <div class="location">Hall A, Building C</div>
                        </div>
                    </div>
                    
                    <div class="exam-item">
                        <div class="exam-details">
                            <h4>Practical Assessment</h4>
                            <p>Database Systems (CS305)</p>
                        </div>
                        <div class="exam-date">
                            <div class="date">Mar 14, 2025</div>
                            <div class="time">2:00 PM - 4:00 PM</div>
                            <div class="location">Computer Lab 2, Building B</div>
                        </div>
                    </div>
                    
                    <div class="exam-item">
                        <div class="exam-details">
                            <h4>Quiz</h4>
                            <p>Web Development (CS302)</p>
                        </div>
                        <div class="exam-date">
                            <div class="date">Mar 18, 2025</div>
                            <div class="time">9:00 AM - 10:00 AM</div>
                            <div class="location">Room 201, Building A</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Section -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-logo">
                <h3>Student Management System</h3>
                <p>© 2025 University Name. All rights reserved.</p>
            </div>
            <div class="footer-links">
                <a href="../privacy.html">Privacy Policy</a>
                <a href="../terms.html">Terms of Use</a>
                <a href="../contact.html">Contact Support</a>
                <a href="../about.html">About</a>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript for dynamic content -->
    <script>
        // Update date and time
        function updateDateTime() {
            const now = new Date();
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const timeOptions = { hour: '2-digit', minute: '2-digit' };
            
            document.getElementById('current-date').textContent = now.toLocaleDateString('en-US', dateOptions);
            document.getElementById('current-time').textContent = now.toLocaleTimeString('en-US', timeOptions);
        }
        
        // Initial update
        updateDateTime();
        
        // Update every minute
        setInterval(updateDateTime, 60000);
        
        // Calendar day click event
        document.querySelectorAll('.calendar-grid .calendar-day').forEach(day => {
            day.addEventListener('click', function() {
                // Remove active class from all days
                document.querySelectorAll('.calendar-day').forEach(d => {
                    d.classList.remove('active-day');
                });
                
                // Add active class to clicked day
                this.classList.add('active-day');
                
                // Here you would typically fetch events for the selected day
                // For now, just a placeholder
                console.log('Day selected:', this.textContent);
            });
        });
        
        // Notification system
        function checkForNewNotifications() {
            // This would typically be an AJAX call to check for new notifications
            // For demonstration, we'll just simulate a notification after 5 seconds
            setTimeout(() => {
                showNotification('New assignment added', 'A new assignment for Web Development has been added.');
            }, 5000);
        }
        
        function showNotification(title, message) {
            // Check if browser supports notifications
            if (!("Notification" in window)) {
                console.log("This browser does not support desktop notifications");
                return;
            }
            
            // Check if permission is already granted
            if (Notification.permission === "granted") {
                createNotification(title, message);
            }
            // Otherwise, request permission
            else if (Notification.permission !== "denied") {
                Notification.requestPermission().then(function (permission) {
                    if (permission === "granted") {
                        createNotification(title, message);
                    }
                });
            }
        }
        
        function createNotification(title, message) {
            const notification = new Notification(title, {
                body: message,
                icon: '../assets/images/logo.png'
            });
            
            notification.onclick = function() {
                window.focus();
                this.close();
            };
        }
        
        // Check for notifications when page loads
        checkForNewNotifications();
    </script>
</body>
</html>