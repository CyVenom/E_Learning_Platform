<?php
$conn = new mysqli("localhost", "root", "", "learning_platform");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Check if the email already exists
    $check_query = "SELECT * FROM users WHERE email = '$email'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        // If the email already exists, redirect to login or show a message
        $error = "An account with this email already exists. Please <a href='login.php'>log in</a>.";
    } else {
        // Insert the new user into the database
        $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
        if ($conn->query($query)) {
            header("Location: login.php?message=registration_successful");
            exit();
        } else {
            $error = "Registration failed! Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Register</title>
</head>
<body>
    <div class="container">
        <h2 class="title">Register</h2>
        <form method="POST" action="register.php">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select>
            <button type="submit">Register</button>
            <?php if (!empty($error)) echo "<p style='color: red;'>$error</p>"; ?>
        </form>
        <!-- Back to Login Button -->
        <a href="login.php" class="back-btn">Back to Login</a>
    </div>
</body>
</html>
