<?php
session_start();
$conn = new mysqli("localhost", "root", "", "learning_platform");

// Check if the user is a teacher and logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

// Check if the course_id is provided in the URL
if (!isset($_GET['course_id'])) {
    echo "Invalid course ID.";
    exit();
}

$course_id = intval($_GET['course_id']);
$teacher_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);

    $query = "UPDATE courses SET title = '$title', description = '$description' WHERE id = $course_id AND teacher_id = $teacher_id";
    if ($conn->query($query)) {
        header("Location: dashboard_teacher.php?message=course_updated");
        exit();
    } else {
        echo "Failed to update course!";
    }
}

// Fetch course details
$query = "SELECT * FROM courses WHERE id = $course_id AND teacher_id = $teacher_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    echo "You do not have access to edit this course.";
    exit();
}

$course = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles_edit_course.css">
    <title>Edit Course</title>
</head>
<body>
    <div class="container">
        <h2>Edit Course</h2>
        <form method="POST" action="edit_course.php?course_id=<?php echo $course_id; ?>">
            <input type="text" name="title" value="<?php echo htmlspecialchars($course['title']); ?>" required>
            <textarea name="description" required><?php echo htmlspecialchars($course['description']); ?></textarea>
            <button type="submit">Update Course</button>
        </form>
        <!-- Back Button -->
        <a href="dashboard_teacher.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
