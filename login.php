<?php
session_start();
$conn = new mysqli("localhost", "root", "", "learning_platform");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            // Redirect based on user role
            if ($user['role'] == 'student') {
                header("Location: dashboard_student.php");
            } elseif ($user['role'] == 'teacher') {
                header("Location: dashboard_teacher.php");
            } else {
                header("Location: dashboard_admin.php");
            }
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No user found with this email!";
    }
}

// Fetch announcements
$query_announcements = "SELECT * FROM announcements ORDER BY created_at DESC LIMIT 5";
$result_announcements = $conn->query($query_announcements);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/learning_platform/login.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <h1 class="title">Welcome to E-Learning Platform</h1>

        <form method="POST" action="login.php">
            <h2>Login</h2>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <?php if (!empty($error)) echo "<p>$error</p>"; ?>
            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        </form>

        <h3>Latest Announcements</h3>
        <ul>
            <?php if ($result_announcements->num_rows > 0) { ?>
                <?php while ($announcement = $result_announcements->fetch_assoc()) { ?>
                    <li><?php echo htmlspecialchars($announcement['message']); ?> (Posted on: <?php echo htmlspecialchars($announcement['created_at']); ?>)</li>
                <?php } ?>
            <?php } else { ?>
                <li>No announcements available at the moment.</li>
            <?php } ?>
        </ul>
    </div>
</body>
</html>


