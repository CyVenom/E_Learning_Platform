<?php
session_start();
$conn = new mysqli("localhost", "root", "", "learning_platform");

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

$error = ''; // Initialize error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $teacher_id = $_SESSION['user_id'];

    // Insert course into the database
    $query = "INSERT INTO courses (title, description, teacher_id) VALUES ('$title', '$description', $teacher_id)";
    if ($conn->query($query)) {
        // Redirect to teacher dashboard with a success message
        header("Location: dashboard_teacher.php?message=course_added");
        exit();
    } else {
        $error = "Failed to add course! Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles_add_course.css">
    <title>Add Course</title>
</head>
<body>
    <div class="container">
        <h2>Add Course</h2>
        <form method="POST" action="add_course.php">
            <input type="text" name="title" placeholder="Course Title" required>
            <textarea name="description" placeholder="Course Description" required></textarea>
            <button type="submit">Add Course</button>
            <?php if (!empty($error)) echo "<p>$error</p>"; ?>
        </form>

        <!-- Back Button -->
        <a href="dashboard_teacher.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
