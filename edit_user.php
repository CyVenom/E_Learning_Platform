<?php
session_start();
$conn = new mysqli("localhost", "root", "", "learning_platform");

// Ensure the admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Check if the user_id is provided
if (!isset($_GET['user_id'])) {
    die("Invalid request. User ID is missing.");
}

$user_id = intval($_GET['user_id']);

// Fetch user details
$query = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();

// Handle form submission to update the user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);

    // Update user details
    $update_query = "UPDATE users SET name = '$name', email = '$email', role = '$role' WHERE id = $user_id";

    if ($conn->query($update_query)) {
        header("Location: dashboard_admin.php?message=user_updated");
        exit();
    } else {
        $error = "Failed to update the user details.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        /* General Body Styling */
body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, rgba(58, 123, 213, 0.9), rgba(58, 213, 196, 0.9));
    background-attachment: fixed;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    color: #fff;
}

/* Glass Effect Container */
.container {
    background: rgba(255, 255, 255, 0.15);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 15px;
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    padding: 30px;
    max-width: 500px;
    width: 90%;
    text-align: center;
    box-sizing: border-box;
    animation: fadeIn 0.5s ease-in-out;
}

/* Fade-in Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Title Styling */
h2 {
    font-size: 2.2rem;
    font-weight: bold;
    margin-bottom: 20px;
    color: #fff;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
}

/* Form Styling */
form input, form select, textarea {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.25);
    color: #333;
    font-size: 1rem;
    outline: none;
    transition: all 0.3s ease-in-out;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

form input:focus, form select:focus, textarea:focus {
    background: rgba(255, 255, 255, 0.35);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.6);
}

/* Button Styling */
form button {
    width: 100%;
    padding: 12px;
    margin-top: 10px;
    background: linear-gradient(135deg, rgba(58, 123, 213, 0.8), rgba(58, 213, 196, 0.8));
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1rem;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

form button:hover {
    background: linear-gradient(135deg, rgba(58, 213, 196, 0.9), rgba(58, 123, 213, 0.9));
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
}

/* Back Button */
.back-btn {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    background: rgba(255, 255, 255, 0.15);
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    border: 1px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease-in-out;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.back-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    color: #333;
    text-decoration: none;
    border: 1px solid rgba(255, 255, 255, 0.6);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
}

/* Error Message */
form p {
    color: red;
    font-size: 0.9rem;
    margin-top: 10px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        width: 90%;
        padding: 20px;
    }

    h2 {
        font-size: 2rem;
    }

    form button {
        font-size: 0.9rem;
        padding: 10px;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Edit User</h2>
        <form method="POST" action="edit_user.php?user_id=<?php echo $user_id; ?>">
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" placeholder="Full Name" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Email" required>
            <select name="role" required>
                <option value="student" <?php echo $user['role'] == 'student' ? 'selected' : ''; ?>>Student</option>
                <option value="teacher" <?php echo $user['role'] == 'teacher' ? 'selected' : ''; ?>>Teacher</option>
                <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Administrator</option>
            </select>
            <button type="submit">Update User</button>
            <?php if (isset($error)) echo "<p>$error</p>"; ?>
        </form>
        <a href="dashboard_admin.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>

