<?php
session_start();
$conn = new mysqli("localhost", "root", "", "learning_platform");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

// Check if the course_id is provided
if (!isset($_GET['course_id'])) {
    die("Invalid request. Course ID is missing.");
}

$course_id = intval($_GET['course_id']);
$teacher_id = $_SESSION['user_id'];

// Verify if the course belongs to the logged-in teacher
$check_query = "SELECT * FROM courses WHERE id = $course_id AND teacher_id = $teacher_id";
$result = $conn->query($check_query);

if ($result->num_rows == 0) {
    die("You do not have access to delete this course.");
}

// Delete all enrollments associated with the course
$delete_enrollments_query = "DELETE FROM enrollments WHERE course_id = $course_id";
if (!$conn->query($delete_enrollments_query)) {
    die("Failed to delete enrollments: " . $conn->error);
}

// Delete the course
$delete_course_query = "DELETE FROM courses WHERE id = $course_id";
if ($conn->query($delete_course_query)) {
    header("Location: dashboard_teacher.php?message=course_deleted");
    exit();
} else {
    die("Failed to delete the course: " . $conn->error);
}
?>
