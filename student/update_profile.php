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

// Fetch current student data
$stmt = $conn->prepare("SELECT student_id, name, email, phone, course FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $course = trim($_POST['course']);

    if (!empty($name) && !empty($email) && !empty($course)) {
        $stmt = $conn->prepare("UPDATE students SET name = ?, email = ?, phone = ?, course = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $email, $phone, $course, $student_id);

        if ($stmt->execute()) {
            $success_message = "Profile updated successfully!";
            // Refresh student data
            $stmt = $conn->prepare("SELECT student_id, name, email, phone, course FROM students WHERE id = ?");
            $stmt->bind_param("i", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $student = $result->fetch_assoc();
            $stmt->close();
        } else {
            $error_message = "Error updating profile: " . $conn->error;
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
    <title>Update Profile | Student Management System</title>
    <link rel="stylesheet" href="../assets/css/update_profile_style.css">
</head>
<body>

    <div class="container">
        <div class="form-card">
            <h1>Update Profile</h1>
            <p class="subtitle">Edit your profile details below.</p>

            <?php if (isset($success_message)) echo '<div class="success-msg">' . $success_message . '</div>'; ?>
            <?php if (isset($error_message)) echo '<div class="error-msg">' . $error_message . '</div>'; ?>

            <form method="POST" action="update_profile.php">
                <div class="input-group">
                    <label for="student_id">Student ID *</label>
                    <input type="text" name="student_id" id="student_id" value="<?php echo htmlspecialchars($student['student_id']); ?>" readonly>
                </div>

                <div class="input-group">
                    <label for="name">Full Name *</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                </div>

                <div class="input-group">
                    <label for="email">Email Address *</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                </div>

                <div class="input-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($student['phone']); ?>">
                </div>

                <div class="input-group">
                    <label for="course">Course *</label>
                    <input type="text" name="course" id="course" value="<?php echo htmlspecialchars($student['course']); ?>" required>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-submit">Update Profile</button>
                    <a href="student_dashboard.php" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>