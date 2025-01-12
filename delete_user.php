<?php
session_start();
$conn = new mysqli("localhost", "root", "", "learning_platform");

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Check if the user_id is provided in the URL
if (!isset($_GET['user_id'])) {
    header("Location: dashboard_admin.php?message=invalid_user");
    exit();
}

$user_id = intval($_GET['user_id']);

// Ensure the admin is not trying to delete themselves
if ($user_id == $_SESSION['user_id']) {
    header("Location: dashboard_admin.php?message=cannot_delete_self");
    exit();
}

// Check if the user exists
$query_check_user = "SELECT * FROM users WHERE id = $user_id";
$result_check_user = $conn->query($query_check_user);

if ($result_check_user->num_rows == 0) {
    header("Location: dashboard_admin.php?message=user_not_found");
    exit();
}

// Delete all enrollments associated with the user (if they are a student)
$query_delete_enrollments = "DELETE FROM enrollments WHERE student_id = $user_id";
$conn->query($query_delete_enrollments);

// Delete all courses created by the user (if they are a teacher)
$query_delete_courses = "DELETE FROM courses WHERE teacher_id = $user_id";
$conn->query($query_delete_courses);

// Delete the user
$query_delete_user = "DELETE FROM users WHERE id = $user_id";

if ($conn->query($query_delete_user)) {
    header("Location: dashboard_admin.php?message=user_deleted");
    exit();
} else {
    header("Location: dashboard_admin.php?message=delete_failed");
    exit();
}
?>
