<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db_connect.php';

// Get student ID from URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM students WHERE id = $id");
    $student = $result->fetch_assoc();
} else {
    header("Location: admin_dashboard.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = trim($_POST['student_id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $course = trim($_POST['course']);

    if (!empty($student_id) && !empty($name) && !empty($email) && !empty($course)) {
        $stmt = $conn->prepare("UPDATE students SET student_id=?, name=?, email=?, phone=?, course=? WHERE id=?");
        $stmt->bind_param("sssssi", $student_id, $name, $email, $phone, $course, $id);

        if ($stmt->execute()) {
            $success_message = "Student updated successfully!";
            // Refresh student data
            $result = $conn->query("SELECT * FROM students WHERE id = $id");
            $student = $result->fetch_assoc();
        } else {
            $error_message = "Error updating student: " . $conn->error;
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
    <title>Edit Student | Student Management System</title>
    <link rel="stylesheet" href="../assets/css/edit_student_style.css">
</head>
<body>

    <div class="container">
        <div class="form-card">
            <h1>Edit Student</h1>
            <p class="subtitle">Update the student’s details below.</p>

            <?php if (isset($success_message)) echo '<div class="success-msg">' . $success_message . '</div>'; ?>
            <?php if (isset($error_message)) echo '<div class="error-msg">' . $error_message . '</div>'; ?>

            <form method="POST" action="edit_student.php?id=<?php echo $id; ?>">
                <div class="input-group">
                    <label for="student_id">Student ID *</label>
                    <input type="text" name="student_id" id="student_id" value="<?php echo $student['student_id']; ?>" required>
                </div>

                <div class="input-group">
                    <label for="name">Full Name *</label>
                    <input type="text" name="name" id="name" value="<?php echo $student['name']; ?>" required>
                </div>

                <div class="input-group">
                    <label for="email">Email Address *</label>
                    <input type="email" name="email" id="email" value="<?php echo $student['email']; ?>" required>
                </div>

                <div class="input-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="<?php echo $student['phone']; ?>">
                </div>

                <div class="input-group">
                    <label for="course">Course *</label>
                    <input type="text" name="course" id="course" value="<?php echo $student['course']; ?>" required>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-submit">Update Student</button>
                    <a href="admin_dashboard.php" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
