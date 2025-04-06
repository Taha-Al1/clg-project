<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db_connect.php';

// Get summary statistics
$totalStudentsQuery = "SELECT COUNT(*) as total FROM students";
$totalStudentsResult = $conn->query($totalStudentsQuery);
$totalStudents = $totalStudentsResult->fetch_assoc()['total'];

$totalCoursesQuery = "SELECT COUNT(DISTINCT course) as total FROM students";
$totalCoursesResult = $conn->query($totalCoursesQuery);
$totalCourses = $totalCoursesResult->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | College Management System</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chart.js/3.7.0/chart.min.js"></script>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo-container">
            <img src="../assets/uploads/uni.png" alt="College Logo" class="college-logo">
            <h2>Admin Portal</h2>
        </div>
        <div class="menu-container">
            <div class="menu-section">
                <p class="menu-heading">MAIN MENU</p>
                <a href="admin_dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="academic_calendar.php"><i class="fas fa-calendar-alt"></i> Academic Calendar</a>
                <a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a>
            </div>
            
            <div class="menu-section">
                <p class="menu-heading">STUDENT MANAGEMENT</p>
                <a href="student_list.php"><i class="fas fa-user-graduate"></i> All Students</a>
                <a href="add_student.php"><i class="fas fa-user-plus"></i> Add Student</a>
                <a href="search_student.php"><i class="fas fa-search"></i> Search Student</a>
                <a href="student_attendance.php"><i class="fas fa-clipboard-check"></i> Attendance</a>
            </div>
            
            <div class="menu-section">
                <p class="menu-heading">ACADEMIC</p>
                <a href="courses.php"><i class="fas fa-book"></i> Courses</a>
                <a href="departments.php"><i class="fas fa-building"></i> Departments</a>
                <a href="faculty.php"><i class="fas fa-chalkboard-teacher"></i> Faculty</a>
                <a href="exam_results.php"><i class="fas fa-file-alt"></i> Exam Results</a>
            </div>
            
            <div class="menu-section">
                <p class="menu-heading">SETTINGS</p>
                <a href="profile.php"><i class="fas fa-user-cog"></i> My Profile</a>
                <a href="system_settings.php"><i class="fas fa-cogs"></i> System Settings</a>
                <a href="../auth/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-navbar">
            <div class="toggle-sidebar">
                <i class="fas fa-bars"></i>
            </div>
            <div class="search-container">
                <input type="text" placeholder="Search..." class="search-input">
                <button class="search-btn"><i class="fas fa-search"></i></button>
            </div>
            <div class="user-menu">
                <div class="notifications">
                    <i class="fas fa-bell"></i>
                    <span class="badge">3</span>
                </div>
                <div class="messages">
                    <i class="fas fa-envelope"></i>
                    <span class="badge">5</span>
                </div>
                <div class="profile-dropdown">
                    <img src="../assets/uploads/images.png" alt="Admin Profile" class="avatar">
                    <span><?php echo $_SESSION['user_name']; ?></span>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </div>

        <div class="dashboard-header">
            <h1>Welcome to College Management System</h1>
            <p class="current-date"><?php echo date('l, F j, Y'); ?></p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card primary">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-card-info">
                        <h3><?php echo $totalStudents; ?></h3>
                        <p>Total Students</p>
                    </div>
                </div>
                <div class="stat-card-footer">
                    <a href="student_list.php">View Details <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="stat-card success">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-card-info">
                        <h3><?php echo $totalCourses; ?></h3>
                        <p>Active Courses</p>
                    </div>
                </div>
                <div class="stat-card-footer">
                    <a href="courses.php">View Details <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="stat-card warning">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-card-info">
                        <h3>45</h3>
                        <p>Faculty Members</p>
                    </div>
                </div>
                <div class="stat-card-footer">
                    <a href="faculty.php">View Details <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="stat-card danger">
                <div class="stat-card-body">
                    <div class="stat-card-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-card-info">
                        <h3>8</h3>
                        <p>Upcoming Events</p>
                    </div>
                </div>
                <div class="stat-card-footer">
                    <a href="events.php">View Details <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-container">
            <div class="chart-card">
                <div class="card-header">
                    <h2>Enrollment Trends</h2>
                    <div class="card-actions">
                        <button class="btn-refresh"><i class="fas fa-sync"></i></button>
                        <div class="dropdown">
                            <button class="btn-options"><i class="fas fa-ellipsis-v"></i></button>
                            <div class="dropdown-content">
                                <a href="#">This Year</a>
                                <a href="#">Last Year</a>
                                <a href="#">All Time</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <!-- <canvas id="enrollmentChart"></canvas> -->
                    </div>
                    <div class="chart-insights">
                        <h3>Enrollment Insights</h3>
                        <p>The line chart above illustrates the enrollment trends over the selected time period. It provides insights into the number of students enrolled each month, helping administrators track growth and identify patterns.</p>
                        <div class="insight-metrics">
                            <div class="metric">
                                <span class="metric-value">700</span>
                                <span class="metric-label">July 2025</span>
                                <div class="progress-bar">
                                    <div class="progress" style="width: 100%; background-color: rgba(54, 162, 235, 1);"></div>
                                </div>
                            </div>
                            <div class="metric">
                                <span class="metric-value">600</span>
                                <span class="metric-label">June 2025</span>
                                <div class="progress-bar">
                                    <div class="progress" style="width: 85%; background-color: rgba(54, 162, 235, 1);"></div>
                                </div>
                            </div>
                            <div class="metric">
                                <span class="metric-value">500</span>
                                <span class="metric-label">May 2025</span>
                                <div class="progress-bar">
                                    <div class="progress" style="width: 71%; background-color: rgba(54, 162, 235, 1);"></div>
                                </div>
                            </div>
                        </div>
                        <div class="action-links">
                            <a href="enrollment_report.php" class="detail-link"><i class="fas fa-chart-line"></i> Detailed Report</a>
                            <a href="#" class="export-link"><i class="fas fa-download"></i> Export Data</a>
                        </div>
                    </div>
                </div>
            </div>
            

            <div class="chart-card">
                <div class="card-header">
                    <h2>Department Distribution</h2>
                    <div class="card-actions">
                        <button class="btn-refresh"><i class="fas fa-sync"></i></button>
                        <div class="dropdown">
                            <button class="btn-options"><i class="fas fa-ellipsis-v"></i></button>
                            <div class="dropdown-content">
                                <a href="#">This Year</a>
                                <a href="#">Last Year</a>
                                <a href="#">All Time</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <!-- <canvas id="departmentChart"></canvas> -->
                    </div>
                    <div class="chart-insights">
                        <h3>Department Insights</h3>
                        <p>The chart shows student distribution across departments for the selected time period. Science and Engineering lead with a combined 55% of total enrollment.</p>
                        <div class="insight-metrics">
                            <div class="metric">
                                <span class="metric-value">20%</span>
                                <span class="metric-label">Computer Science</span>
                                <div class="progress-bar">
                                    <div class="progress cs-bg" style="width: 20%"></div>
                                </div>
                            </div>
                            <div class="metric">
                                <span class="metric-value">15%</span>
                                <span class="metric-label">Business</span>
                                <div class="progress-bar">
                                    <div class="progress business-bg" style="width: 15%"></div>
                                </div>
                            </div>
                            <div class="metric">
                                <span class="metric-value">10%</span>
                                <span class="metric-label">Arts</span>
                                <div class="progress-bar">
                                    <div class="progress arts-bg" style="width: 10%"></div>
                                </div>
                            </div>
                            <div class="metric">
                                <span class="metric-value">25%</span>
                                <span class="metric-label">Engineering</span>
                                <div class="progress-bar">
                                    <div class="progress engineering-bg" style="width: 25%"></div>
                                </div>
                            </div>
                            <div class="metric">
                                <span class="metric-value">30%</span>
                                <span class="metric-label">Science</span>
                                <div class="progress-bar">
                                    <div class="progress science-bg" style="width: 30%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="action-links">
                            <a href="departments.php" class="detail-link"><i class="fas fa-chart-pie"></i> Detailed Report</a>
                            <a href="#" class="export-link"><i class="fas fa-download"></i> Export Data</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity and Deadlines Section -->
        <div class="flex-container">
            <!-- Recent Activity -->
            <div class="card activity-card">
                <div class="card-header">
                    <h2>Recent Activities</h2>
                    <a href="activity_logs.php" class="view-all">View All</a>
                </div>
                <div class="card-body">
                    <ul class="activity-list">
                        <?php
                        // if ($recentActivitiesResult && $recentActivitiesResult->num_rows > 0) {
                        //     while ($activity = $recentActivitiesResult->fetch_assoc()) {
                        //         echo '<li class="activity-item">
                        //                 <div class="activity-icon"><i class="' . $activity['icon_class'] . '"></i></div>
                        //                 <div class="activity-details">
                        //                     <p class="activity-text">' . $activity['description'] . '</p>
                        //                     <span class="activity-time">' . date('M d, Y h:i A', strtotime($activity['timestamp'])) . '</span>
                        //                 </div>
                        //             </li>';
                        //     }
                        // } else {
                        //     echo '<li class="activity-item">No recent activities</li>';
                        // }
                        ?>


                        <!-- Fallback activities if database table doesn't exist -->
                        <li class="activity-item">
                            <div class="activity-icon"><i class="fas fa-user-plus"></i></div>
                            <div class="activity-details">
                                <p class="activity-text">New student <strong>James Wilson</strong> was added</p>
                                <span class="activity-time">Mar 1, 2025 11:45 AM</span>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon"><i class="fas fa-edit"></i></div>
                            <div class="activity-details">
                                <p class="activity-text">Student <strong>Sarah Johnson</strong> information updated</p>
                                <span class="activity-time">Feb 28, 2025 03:22 PM</span>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon"><i class="fas fa-calendar-plus"></i></div>
                            <div class="activity-details">
                                <p class="activity-text">New event <strong>Spring Semester Registration</strong> added</p>
                                <span class="activity-time">Feb 27, 2025 09:15 AM</span>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon"><i class="fas fa-trash-alt"></i></div>
                            <div class="activity-details">
                                <p class="activity-text">Student <strong>Michael Brown</strong> was removed</p>
                                <span class="activity-time">Feb 26, 2025 02:45 PM</span>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon"><i class="fas fa-file-upload"></i></div>
                            <div class="activity-details">
                                <p class="activity-text">New course materials uploaded for <strong>Computer Science 101</strong></p>
                                <span class="activity-time">Feb 25, 2025 10:30 AM</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Upcoming Deadlines -->
            <div class="card deadlines-card">
                <div class="card-header">
                    <h2>Upcoming Deadlines</h2>
                    <a href="deadlines.php" class="view-all">View All</a>
                </div>
                <div class="card-body">
                    <ul class="deadline-list">
                        <?php
                        // if ($upcomingDeadlinesResult && $upcomingDeadlinesResult->num_rows > 0) {
                        //     while ($deadline = $upcomingDeadlinesResult->fetch_assoc()) {
                        //         echo '<li class="deadline-item">
                        //                 <div class="deadline-date">' . date('M d', strtotime($deadline['deadline_date'])) . '</div>
                        //                 <div class="deadline-details">
                        //                     <h4>' . $deadline['title'] . '</h4>
                        //                     <p>' . $deadline['description'] . '</p>
                        //                 </div>
                        //             </li>';
                        //     }
                        // } else {

                            // Fallback deadlines if database table doesn't exist
                            ?>
                            <li class="deadline-item">
                                <div class="deadline-date">Mar 15</div>
                                <div class="deadline-details">
                                    <h4>Final Exam Registration</h4>
                                    <p>Last day for students to register for spring semester final exams</p>
                                </div>
                            </li>
                            <li class="deadline-item">
                                <div class="deadline-date">Mar 20</div>
                                <div class="deadline-details">
                                    <h4>Faculty Meeting</h4>
                                    <p>Mandatory meeting for all faculty members to discuss curriculum changes</p>
                                </div>
                            </li>
                            <li class="deadline-item">
                                <div class="deadline-date">Apr 05</div>
                                <div class="deadline-details">
                                    <h4>Scholarship Applications</h4>
                                    <p>Deadline for submitting scholarship applications for next semester</p>
                                </div>
                            </li>
                       
                    </ul>
                </div>
            </div>
        </div>

        <!-- Student Records Section -->
        <div class="card student-records-card">
            <div class="card-header">
                <h2>Student Records</h2>
                <div class="header-buttons">
                    <a href="add_student.php" class="btn btn-primary"><i class="fas fa-user-plus"></i> Add New</a>
                    <a href="search_student.php" class="btn btn-secondary"><i class="fas fa-search"></i> Search</a>
                    <button class="btn btn-outline-secondary"><i class="fas fa-filter"></i> Filter</button>
                    <button class="btn btn-outline-secondary"><i class="fas fa-download"></i> Export</button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-filters">
                    <div class="show-entries">
                        <span>Show</span>
                        <select class="entries-select">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span>entries</span>
                    </div>
                    <div class="table-search">
                        <input type="text" placeholder="Search records..." class="search-table">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="student-table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="select-all"></th>
                                <th>ID <i class="fas fa-sort"></i></th>
                                <th>Name <i class="fas fa-sort"></i></th>
                                <th>Email <i class="fas fa-sort"></i></th>
                                <th>Phone <i class="fas fa-sort"></i></th>
                                <th>Department <i class="fas fa-sort"></i></th>
                                <th>Course <i class="fas fa-sort"></i></th>
                                <th>Status <i class="fas fa-sort"></i></th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM students LIMIT 10");
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td><input type='checkbox' class='row-checkbox'></td>";
                                    echo "<td>" . $row['student_id'] . "</td>";
                                    echo "<td>" . $row['name'] . "</td>";
                                    echo "<td>" . $row['email'] . "</td>";
                                    echo "<td>" . $row['phone'] . "</td>";
                                    echo "<td>" . ($row['department'] ?? 'Computer Science') . "</td>";
                                    echo "<td>" . $row['course'] . "</td>";
                                    echo "<td><span class='status-badge active'>Active</span></td>";
                                    echo "<td class='actions'>
                                            <a href='view_student.php?id=" . $row['id'] . "' class='btn-icon' title='View'><i class='fas fa-eye'></i></a>
                                            <a href='edit_student.php?id=" . $row['id'] . "' class='btn-icon' title='Edit'><i class='fas fa-edit'></i></a>
                                            <a href='delete_student.php?id=" . $row['id'] . "' class='btn-icon delete' title='Delete' onclick='return confirm(\"Are you sure you want to delete this student?\")'><i class='fas fa-trash-alt'></i></a>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                // Fallback data if no records found
                                ?>
                                <tr>
                                    <td><input type="checkbox" class="row-checkbox"></td>
                                    <td>STU20250001</td>
                                    <td>Jennifer Smith</td>
                                    <td>jennifer.smith@example.edu</td>
                                    <td>(555) 123-4567</td>
                                    <td>Computer Science</td>
                                    <td>Software Engineering</td>
                                    <td><span class="status-badge active">Active</span></td>
                                    <td class="actions">
                                        <a href="view_student.php?id=1" class="btn-icon" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="edit_student.php?id=1" class="btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                                        <a href="delete_student.php?id=1" class="btn-icon delete" title="Delete" onclick="return confirm('Are you sure you want to delete this student?')"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="row-checkbox"></td>
                                    <td>STU20250002</td>
                                    <td>Michael Johnson</td>
                                    <td>michael.johnson@example.edu</td>
                                    <td>(555) 234-5678</td>
                                    <td>Business</td>
                                    <td>Marketing</td>
                                    <td><span class="status-badge active">Active</span></td>
                                    <td class="actions">
                                        <a href="view_student.php?id=2" class="btn-icon" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="edit_student.php?id=2" class="btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                                        <a href="delete_student.php?id=2" class="btn-icon delete" title="Delete" onclick="return confirm('Are you sure you want to delete this student?')"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="row-checkbox"></td>
                                    <td>STU20250003</td>
                                    <td>Emily Davis</td>
                                    <td>emily.davis@example.edu</td>
                                    <td>(555) 345-6789</td>
                                    <td>Arts</td>
                                    <td>Graphic Design</td>
                                    <td><span class="status-badge inactive">On Leave</span></td>
                                    <td class="actions">
                                        <a href="view_student.php?id=3" class="btn-icon" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="edit_student.php?id=3" class="btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                                        <a href="delete_student.php?id=3" class="btn-icon delete" title="Delete" onclick="return confirm('Are you sure you want to delete this student?')"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="row-checkbox"></td>
                                    <td>STU20250004</td>
                                    <td>Daniel Brown</td>
                                    <td>daniel.brown@example.edu</td>
                                    <td>(555) 456-7890</td>
                                    <td>Engineering</td>
                                    <td>Mechanical Engineering</td>
                                    <td><span class="status-badge active">Active</span></td>
                                    <td class="actions">
                                        <a href="view_student.php?id=4" class="btn-icon" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="edit_student.php?id=4" class="btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                                        <a href="delete_student.php?id=4" class="btn-icon delete" title="Delete" onclick="return confirm('Are you sure you want to delete this student?')"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="row-checkbox"></td>
                                    <td>STU20250005</td>
                                    <td>Sophia Wilson</td>
                                    <td>sophia.wilson@example.edu</td>
                                    <td>(555) 567-8901</td>
                                    <td>Science</td>
                                    <td>Biology</td>
                                    <td><span class="status-badge warning">Probation</span></td>
                                    <td class="actions">
                                        <a href="view_student.php?id=5" class="btn-icon" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="edit_student.php?id=5" class="btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                                        <a href="delete_student.php?id=5" class="btn-icon delete" title="Delete" onclick="return confirm('Are you sure you want to delete this student?')"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <div class="showing-entries">Showing 1 to 5 of 45 entries</div>
                    <div class="pagination">
                        <a href="#" class="page-link disabled"><i class="fas fa-chevron-left"></i></a>
                        <a href="#" class="page-link active">1</a>
                        <a href="#" class="page-link">2</a>
                        <a href="#" class="page-link">3</a>
                        <a href="#" class="page-link">4</a>
                        <a href="#" class="page-link">5</a>
                        <a href="#" class="page-link"><i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <footer class="dashboard-footer">
            <div class="footer-content">
                <div>
                <h3 class="College-content">Student Management System</h3>
                <p>&copy; 2025 College Management System. All rights reserved.</p>
            </div>
                <div class="footer-links">
                    <a href="help.php">Help</a>
                    <a href="privacy_policy.php">Privacy Policy</a>
                    <a href="terms.php">Terms of Service</a>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Enrollment Trends Chart
        var enrollmentChart = document.getElementById('enrollmentChart').getContext('2d');
        var myChart = new Chart(enrollmentChart, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Enrollment',
                    data: [100, 200, 300, 400, 500, 600, 700],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Department Distribution Chart
        var departmentChart = document.getElementById('departmentChart').getContext('2d');
        var myChart = new Chart(departmentChart, {
            type: 'doughnut',
            data: {
                labels: ['Computer Science', 'Business', 'Arts', 'Engineering', 'Science'],
                datasets: [{
                    label: 'Department Distribution',
                    data: [20, 15, 10, 25, 30],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            }
        });
    </script>
</body>
</html>