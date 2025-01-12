<?php
session_start();
$conn = new mysqli("localhost", "root", "", "learning_platform");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST['message'];

    $query = "INSERT INTO announcements (message) VALUES ('$message')";
    if ($conn->query($query)) {
        header("Location: dashboard_admin.php");
        exit();
    } else {
        $error = "Failed to add announcement!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Announcement</title>
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
        form {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            box-sizing: border-box;
        }

        /* Title Styling */
        h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #fff;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        }

        /* Input and Textarea Styling */
        textarea {
            width: 100%;
            padding: 15px;
            margin: 15px 0;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.4);
            color: #333;
            font-size: 1rem;
            box-sizing: border-box;
            resize: none;
            height: 100px;
        }

        textarea::placeholder {
            color: #666;
        }

        /* Button Styling */
        button {
            width: 100%;
            padding: 10px;
            background: linear-gradient(135deg, rgba(58, 123, 213, 0.8), rgba(58, 213, 196, 0.8));
            color: #fff;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background: linear-gradient(135deg, rgba(58, 213, 196, 0.8), rgba(58, 123, 213, 0.8));
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Error Message Styling */
        p {
            color: #ff4d4d;
            font-weight: bold;
            margin-top: 10px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            form {
                width: 90%;
                padding: 20px;
            }

            h2 {
                font-size: 1.8rem;
            }

            textarea {
                height: 80px;
            }
        }
    </style>
</head>
<body>
    <form method="POST" action="add_announcement.php">
        <h2>Add Announcement</h2>
        <textarea name="message" placeholder="Enter your announcement" required></textarea>
        <button type="submit">Post Announcement</button>
        <?php if (!empty($error)) echo "<p>$error</p>"; ?>
    </form>
</body>
</html>
