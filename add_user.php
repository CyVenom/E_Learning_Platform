<?php
session_start();
$conn = new mysqli("localhost", "root", "", "learning_platform");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$error = ''; // To hold any error messages
$success = ''; // To hold success messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $role = $conn->real_escape_string($_POST['role']);

    // Check if the email already exists
    $check_query = "SELECT * FROM users WHERE email = '$email'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        $error = "A user with this email already exists.";
    } else {
        // Insert the user into the database
        $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
        if ($conn->query($query)) {
            $success = "User added successfully!";
        } else {
            $error = "Failed to add user. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles_add_user.css">
    <title>Add User</title>
</head>
<body>
    <div class="container">
        <h2>Add New User</h2>
        <form method="POST" action="add_user.php">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
               <option value="admin">Admin</option>
            </select>
            <button type="submit">Add User</button>
        </form>
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php endif; ?>
        <a href="dashboard_admin.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
