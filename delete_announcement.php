<?php
session_start();
$conn = new mysqli("localhost", "root", "", "learning_platform");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['announcement_id'])) {
    die("Invalid request. Announcement ID is missing.");
}

$announcement_id = intval($_GET['announcement_id']);

// Delete the announcement
$query = "DELETE FROM announcements WHERE id = $announcement_id";

if ($conn->query($query)) {
    header("Location: dashboard_admin.php?message=announcement_deleted");
    exit();
} else {
    die("Failed to delete the announcement.");
}
?>
