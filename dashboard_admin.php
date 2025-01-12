<?php
session_start();
$conn = new mysqli("localhost", "root", "", "learning_platform");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all users
$query_users = "SELECT * FROM users";
$result_users = $conn->query($query_users);

// Fetch all courses with their teacher details
$query_courses = "
    SELECT courses.id, courses.title, courses.description, users.name AS teacher_name 
    FROM courses
    INNER JOIN users ON courses.teacher_id = users.id
";
$result_courses = $conn->query($query_courses);

// Fetch all announcements
$query_announcements = "SELECT * FROM announcements ORDER BY created_at DESC";
$result_announcements = $conn->query($query_announcements);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles_admin.css">
    <title>Admin Dashboard</title>
</head>
<body>
<div class="container">
    <h2>Welcome to the Admin Dashboard</h2>
    
    <!-- Message Display Section -->
    <?php if (isset($_GET['message'])): ?>
        <div class="message">
            <?php
            if ($_GET['message'] == 'user_deleted') {
                echo "User successfully deleted.";
            } elseif ($_GET['message'] == 'delete_failed') {
                echo "Failed to delete the user.";
            } elseif ($_GET['message'] == 'user_not_found') {
                echo "User not found.";
            } elseif ($_GET['message'] == 'cannot_delete_self') {
                echo "You cannot delete your own account.";
            } elseif ($_GET['message'] == 'invalid_user') {
                echo "Invalid user.";
            }
            ?>
        </div>
    <?php endif; ?>

    <h3>Manage Users</h3>
    <ul>
        <?php while ($user = $result_users->fetch_assoc()) { ?>
            <li>
                <span><strong><?php echo $user['name']; ?></strong> (<?php echo $user['role']; ?>)</span>
                <a href="edit_user.php?user_id=<?php echo $user['id']; ?>">Edit</a>
                <a href="delete_user.php?user_id=<?php echo $user['id']; ?>">Delete</a>
            </li>
        <?php } ?>
    </ul>
    <a href="add_user.php">Add New User</a>
    
    <h3>Courses Added by Teachers</h3>
    <ul>
        <?php while ($course = $result_courses->fetch_assoc()) { ?>
            <li>
                <strong><?php echo $course['title']; ?></strong> - <?php echo $course['description']; ?> (by <?php echo $course['teacher_name']; ?>)
            </li>
        <?php } ?>
    </ul>
    
    <h3>Announcements</h3>
    <ul>
        <?php while ($announcement = $result_announcements->fetch_assoc()) { ?>
            <li>
                <?php echo $announcement['message']; ?> (Posted on: <?php echo $announcement['created_at']; ?>)
                <a href="edit_announcement.php?announcement_id=<?php echo $announcement['id']; ?>">Edit</a>
                <a href="delete_announcement.php?announcement_id=<?php echo $announcement['id']; ?>">Delete</a>
            </li>
        <?php } ?>
    </ul>
    <a href="add_announcement.php">Add New Announcement</a>
    <a href="logout.php" class="logout-btn">Logout</a>
</div>
</body>
</html>
