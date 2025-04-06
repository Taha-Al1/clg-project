<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db_connect.php';

$user_id = $_SESSION['user_id'];
$upload_dir = '../assets/uploads/';
$success_message = '';
$error_message = '';

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_name = basename($_FILES['profile_picture']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_ext = ['jpg', 'jpeg', 'png'];
        if (in_array($file_ext, $allowed_ext)) {
            $new_file_name = "user_" . $user_id . "_" . time() . "." . $file_ext;
            $file_path = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp, $file_path)) {
                $stmt = $conn->prepare("UPDATE students SET profile_picture = ? WHERE id = ?");
                $stmt->bind_param("si", $new_file_name, $user_id);
                if ($stmt->execute()) {
                    $success_message = "Profile picture uploaded successfully!";
                } else {
                    $error_message = "Database update failed!";
                }
            } else {
                $error_message = "File upload failed!";
            }
        } else {
            $error_message = "Only JPG, JPEG, and PNG files are allowed!";
        }
    } else {
        $error_message = "Please select a file to upload!";
    }
}

// Get current profile picture
$result = $conn->query("SELECT profile_picture FROM students WHERE id = $user_id");
$row = $result->fetch_assoc();
$current_picture = $row['profile_picture'] ? '../assets/uploads/' . $row['profile_picture'] : '../assets/uploads/default.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Profile Picture | Student Management System</title>
    <link rel="stylesheet" href="../assets/css/upload_photo_style.css">
</head>
<body>

    <div class="container">
        <div class="form-card">
            <h1>Upload Profile Picture</h1>
            <p class="subtitle">Choose a photo to upload as your profile picture.</p>

            <?php if ($success_message): ?>
                <div class="success-msg"><?php echo $success_message; ?></div>
            <?php elseif ($error_message): ?>
                <div class="error-msg"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <div class="image-preview">
                <img id="previewImage" src="<?php echo $current_picture; ?>" alt="Profile Picture">
            </div>

            <form method="POST" action="upload_photo.php" enctype="multipart/form-data">
                <div class="input-group">
                    <label for="profile_picture">Select Image *</label>
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/jpeg, image/png" required onchange="previewImage(event)">
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-upload">Upload</button>
                    <a href="student_dashboard.php" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const image = document.getElementById('previewImage');
            image.src = URL.createObjectURL(event.target.files[0]);
            image.onload = () => URL.revokeObjectURL(image.src); // Free memory
        }
    </script>

</body>
</html>
