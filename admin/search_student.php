<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db_connect.php';

// Handle search
$students = [];
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $search = "%$search%";
    $stmt = $conn->prepare("SELECT * FROM students WHERE name LIKE ? OR email LIKE ?");
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
    $students = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Students | Student Management System</title>
    <link rel="stylesheet" href="../assets/css/search_student_style.css">
</head>
<body>

    <div class="container">
        <div class="search-card">
            <h1>Search Students</h1>
            <p class="subtitle">Find students by name or email.</p>

            <form method="GET" action="search_student.php" class="search-form">
                <div class="input-group">
                    <input type="text" name="search" id="search" placeholder="Enter name or email" 
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" required>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="btn-search">Search</button>
                    <a href="admin_dashboard.php" class="btn-cancel">Cancel</a>
                </div>
            </form>

            <?php if (isset($_GET['search'])): ?>
                <div class="result-section">
                    <h2>Search Results</h2>

                    <?php if (count($students) > 0): ?>
                        <div class="table-responsive">
                            <table class="student-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Course</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $student): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                            <td><?php echo htmlspecialchars($student['name']); ?></td>
                                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                                            <td><?php echo htmlspecialchars($student['phone'] ?: 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($student['course']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="no-results">No students found matching your search.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
