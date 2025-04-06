<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db_connect.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = trim($_POST['student_id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $course = trim($_POST['course']);

    if (!empty($student_id) && !empty($name) && !empty($email) && !empty($course)) {
        $stmt = $conn->prepare("INSERT INTO students (student_id, name, email, phone, course) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $student_id, $name, $email, $phone, $course);

        if ($stmt->execute()) {
            $success_message = "Student added successfully!";
        } else {
            $error_message = "Error adding student: " . $conn->error;
        }
    } else {
        $error_message = "All fields marked with * are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student | Student Management System</title>
    <link rel="stylesheet" href="../assets/css/add_student_style.css">
</head>
<body>

    <div class="container">
        <div class="form-card">
            <h1>Add New Student</h1>
            <p class="subtitle">Fill in the details below to add a new student.</p>

            <?php if (isset($success_message)) echo '<div class="success-msg">' . $success_message . '</div>'; ?>
            <?php if (isset($error_message)) echo '<div class="error-msg">' . $error_message . '</div>'; ?>

            <form method="POST" action="add_student.php">
                <div class="input-group">
                    <label for="student_id">Student ID *</label>
                    <input type="text" name="student_id" id="student_id" placeholder="Enter student ID" required>
                </div>

                <div class="input-group">
                    <label for="name">Full Name *</label>
                    <input type="text" name="name" id="name" placeholder="Enter full name" required>
                </div>

                <div class="input-group">
                    <label for="email">Email Address *</label>
                    <input type="email" name="email" id="email" placeholder="Enter email address" required>
                </div>

                <div class="input-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" name="phone" id="phone" placeholder="Enter phone number">
                </div>

                <div class="input-group">
                    <label for="course">Course *</label>
                    <input type="text" name="course" id="course" placeholder="Enter course" required>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-submit">Add Student</button>
                    <a href="admin_dashboard.php" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
