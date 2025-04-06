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

    if (!$student) {
        header("Location: admin_dashboard.php");
        exit();
    }
} else {
    header("Location: admin_dashboard.php");
    exit();
}

// Handle deletion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['confirm_delete'])) {
        $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $success_message = "Student deleted successfully!";
        } else {
            $error_message = "Error deleting student: " . $conn->error;
        }
    } else {
        header("Location: admin_dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student | Student Management System</title>
    <link rel="stylesheet" href="../assets/css/delete_student_style.css">
</head>
<body>

    <div class="container">
        <div class="form-card">
            <h1>Delete Student</h1>

            <?php if (isset($success_message)) : ?>
                <div class="success-msg"><?php echo $success_message; ?></div>
                <div class="form-buttons">
                    <a href="admin_dashboard.php" class="btn-cancel">Back to Dashboard</a>
                </div>
            <?php elseif (isset($error_message)) : ?>
                <div class="error-msg"><?php echo $error_message; ?></div>
                <div class="form-buttons">
                    <a href="admin_dashboard.php" class="btn-cancel">Back to Dashboard</a>
                </div>
            <?php else : ?>
                <p class="subtitle">Are you sure you want to delete the following student?</p>

                <div class="student-details">
                    <p><strong>Student ID:</strong> <?php echo $student['student_id']; ?></p>
                    <p><strong>Name:</strong> <?php echo $student['name']; ?></p>
                    <p><strong>Email:</strong> <?php echo $student['email']; ?></p>
                    <p><strong>Phone:</strong> <?php echo $student['phone'] ?: 'N/A'; ?></p>
                    <p><strong>Course:</strong> <?php echo $student['course']; ?></p>
                </div>

                <form method="POST" action="delete_student.php?id=<?php echo $id; ?>">
                    <div class="form-buttons">
                        <button type="submit" name="confirm_delete" class="btn-delete">Delete</button>
                        <a href="admin_dashboard.php" class="btn-cancel">Cancel</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
