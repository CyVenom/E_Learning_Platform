<?php
session_start();
$conn = new mysqli("localhost", "root", "", "learning_platform");

// Ensure the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

// Check if course_id is provided in the URL
if (!isset($_GET['course_id'])) {
    echo "Invalid course ID.";
    exit();
}

$course_id = intval($_GET['course_id']);
$teacher_id = $_SESSION['user_id'];

// Verify if the course belongs to the logged-in teacher
$query = "SELECT * FROM courses WHERE id = $course_id AND teacher_id = $teacher_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    echo "You do not have access to view feedback for this course.";
    exit();
}

// Fetch feedback for the course
$feedback_query = "
    SELECT users.name AS student_name, feedback.comment 
    FROM feedback
    INNER JOIN users ON feedback.user_id = users.id
    WHERE feedback.course_id = $course_id
";
$feedback_result = $conn->query($feedback_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles_feedback.css">
    <title>Course Feedback</title>
</head>
<body>
    <div class="container">
        <h2>Feedback for Course</h2>
        <?php if ($feedback_result->num_rows > 0): ?>
            <ul>
                <?php while ($feedback = $feedback_result->fetch_assoc()): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($feedback['student_name']); ?>:</strong>
                        <p><?php echo htmlspecialchars($feedback['comment']); ?></p>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No feedback available for this course.</p>
        <?php endif; ?>
        <a href="dashboard_teacher.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
