<?php
session_start();
$conn = new mysqli("localhost", "root", "", "learning_platform");

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle logout functionality
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Fetch courses added by the teacher
$query = "SELECT * FROM courses WHERE teacher_id = $user_id";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles_teacher.css">
    <title>Teacher Dashboard</title>
</head>
<body>
    <div class="container">
        <h1>Welcome to the Teacher Dashboard</h1>

        <h2>Your Courses</h2>
        <ul>
            <?php while ($course = $result->fetch_assoc()) { ?>
                <li>
                    <strong><?php echo $course['title']; ?></strong> - <?php echo $course['description']; ?>
                    <a href="manage_course.php?course_id=<?php echo $course['id']; ?>" class="manage-btn">Manage</a>

                </li>
                <li>
    <strong><?php echo $course['title']; ?></strong> - <?php echo $course['description']; ?>
    <a href="view_feedback.php?course_id=<?php echo $course['id']; ?>" class="view-feedback-btn">View Feedback</a>
</li>

            <?php } ?>
        </ul>
        <a href="add_course.php" class="add-course-btn">Add New Course</a>
        <a href="?logout=true" class="logout-btn">Logout</a>
    </div>
</body>
</html>
