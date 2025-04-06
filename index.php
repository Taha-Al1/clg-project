<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Student Portal | Skyline College</title>
    <link rel="stylesheet" href="assets/css/index_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="portal-header">
        <div class="header-content">
            <div class="header-text">
                <h1>Horizen College Student Portal</h1>
                <p>Empowering Education Through Technology</p>
            </div>
        </div>
    </header>

    <nav class="main-nav">
        <ul>
            <li><a href="#"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="#"><i class="fas fa-info-circle"></i> About</a></li>
            <li><a href="#"><i class="fas fa-envelope"></i> Contact</a></li>
            <li><a href="#"><i class="fas fa-calendar-alt"></i> Events</a></li>
            <li><a href="auth/register.php"><i class="fas fa-user-plus"></i> Register</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="portal-container">
            <section class="welcome-section">
                <div class="welcome-card">
                    <h2>Welcome to Your Student Portal</h2>
                    <p class="subtitle">Access your academic resources, grades, and college services</p>
                    
                    <div class="login-options">
                        <div class="role-card admin-role">
                            <i class="fas fa-user-shield role-icon"></i>
                            <h3>Administrator Access</h3>
                            <p>Manage academic records, student data, and system configuration</p>
                            <a href="auth/login.php?role=admin" class="btn-admin">Staff Login</a>
                        </div>

                        <div class="role-card student-role">
                            <i class="fas fa-user-graduate role-icon"></i>
                            <h3>Student Access</h3>
                            <p>Access course materials, grades, and academic services</p>
                            <a href="auth/login.php?role=student" class="btn-student">Student Login</a>
                        </div>
                    </div>
                </div>
            </section>

            <aside class="news-section">
                <div class="announcement-box">
                    <h3><i class="fas fa-bullhorn"></i> Latest Announcements</h3>
                    <ul class="announcement-list">
                        <li>Fall 2024 Registration Now Open</li>
                        <li>New Campus Library Hours</li>
                        <li>Scholarship Application Deadline Extended</li>
                    </ul>
                </div>

                <div>
                    <h3><i class="fas fa-link"></i> Quick Links</h3>
                    <ul class="quick-links">
                        <li><a href="#"><i class="fas fa-book"></i> Course Catalog</a></li>
                        <li><a href="#"><i class="fas fa-calendar-check"></i> Academic Calendar</a></li>
                        <li><a href="#"><i class="fas fa-dollar-sign"></i> Payment Portal</a></li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>

    <footer class="portal-footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>Contact Us</h4>
                <p>123 College Road</p>
                <p>Skyline City, ST 12345</p>
                <p>Phone: (555) 123-4567</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul class="privacy">
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Accessibility</a></li>
                    <li><a href="#">Emergency Information</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Follow Us</h4>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Skyline College. All rights reserved. | <a href="#">Terms of Service</a></p>
        </div>
    </footer>
</body>
</html>