<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Redirect based on the user's role
$role = $_SESSION['role'];

if ($role == 'student') {
    header("Location: dashboard_student.php");
    exit();
} elseif ($role == 'teacher') {
    header("Location: dashboard_teacher.php");
    exit();
} elseif ($role == 'admin') {
    header("Location: dashboard_admin.php");
    exit();
} else {
    // Handle invalid roles (if necessary)
    echo "Invalid role detected!";
}
