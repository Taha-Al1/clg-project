<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Student Management System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="login-card">
            <h1>Login</h1>
            <p class="subtitle">Welcome back! Please login to your account.</p>

            <?php
            session_start();
            include '../config/db_connect.php';

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $email = trim($_POST['email']);
                $password = trim($_POST['password']);

                $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result()->fetch_assoc();

                if ($result && password_verify($password, $result['password'])) {
                    $_SESSION['student_id'] = $user['student_id'];
                    $_SESSION['user_id'] = $result['id'];
                    $_SESSION['user_name'] = $result['name'];
                    $_SESSION['user_role'] = $result['role'];

                    if ($result['role'] == 'admin') {
                        header("Location: ../admin/admin_dashboard.php");
                    } else {
                        header("Location: ../student/student_dashboard.php");
                    }
                    exit();
                } else {
                    echo '<div class="error-msg">Invalid email or password!</div>';
                }
            }
            ?>

            <form method="POST" action="login.php">
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" placeholder="Enter your email" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                        <span class="toggle-password" onclick="togglePasswordVisibility()">
                            <i id="eye-icon" class="fa fa-eye"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn-login">Login</button>
            </form>

            <div class="footer">
                <p>&copy; 2025 Student Management System</p>
            </div>
        </div>
    </div>
    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById("password");
            var eyeIcon = document.getElementById("eye-icon");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>
