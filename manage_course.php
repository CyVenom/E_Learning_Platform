<?php
session_start();
$conn = new mysqli("localhost", "root", "", "learning_platform");

// Ensure the user is a teacher and logged in
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

// Verify if the course belongs to the logged-in teacher
$query = "SELECT * FROM courses WHERE id = $course_id AND teacher_id = $teacher_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    echo "You do not have access to manage this course.";
    exit();
}

$course = $result->fetch_assoc();

// Fetch students enrolled in the course
$enrollments_query = "
    SELECT users.id, users.name, users.email 
    FROM enrollments 
    INNER JOIN users ON enrollments.student_id = users.id 
    WHERE enrollments.course_id = $course_id
";
$enrollments_result = $conn->query($enrollments_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles_manage_course.css">
    <title>Manage Course</title>
</head>
<body>
    <div class="container">
        <h1>Manage Course</h1>
        <h2><?php echo htmlspecialchars($course['title']); ?></h2>
        <p class="description"><?php echo htmlspecialchars($course['description']); ?></p>

        <h3>Enrolled Students</h3>
        <?php if ($enrollments_result->num_rows > 0) { ?>
            <ul class="students-list">
                <?php while ($student = $enrollments_result->fetch_assoc()) { ?>
                    <li>
                        <strong><?php echo htmlspecialchars($student['name']); ?></strong> (<?php echo htmlspecialchars($student['email']); ?>)
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p class="no-students">No students have enrolled in this course yet.</p>
        <?php } ?>

        <h3>Actions</h3>
        <ul class="actions-list">
            <li><a href="edit_course.php?course_id=<?php echo $course_id; ?>" class="action-link">Edit Course</a></li>
            <li><a href="delete_course.php?course_id=<?php echo $course_id; ?>" class="action-link delete">Delete Course</a></li>
        </ul>

        <!-- Back Button -->
        <a href="dashboard_teacher.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
