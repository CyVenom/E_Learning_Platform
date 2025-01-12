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

// Fetch the existing announcement
$query = "SELECT * FROM announcements WHERE id = $announcement_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    die("Announcement not found.");
}

$announcement = $result->fetch_assoc();

// Handle form submission to update the announcement
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $conn->real_escape_string($_POST['message']);
    $update_query = "UPDATE announcements SET message = '$message' WHERE id = $announcement_id";

    if ($conn->query($update_query)) {
        header("Location: dashboard_admin.php?message=announcement_updated");
        exit();
    } else {
        $error = "Failed to update the announcement.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Announcement</title>
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
            color: #000;
        }

        /* Glass Effect Container */
        .container {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 600px;
            width: 90%;
            text-align: center;
            box-sizing: border-box;
        }

        /* Title Styling */
        h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #000;
        }

        /* Form Styling */
        form textarea {
            width: 100%;
            padding: 15px;
            margin: 15px 0;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            font-size: 1rem;
            box-sizing: border-box;
            resize: none;
        }

        form button {
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

        form button:hover {
            background: linear-gradient(135deg, rgba(58, 213, 196, 0.8), rgba(58, 123, 213, 0.8));
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Back Button Styling */
        .back-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background: linear-gradient(135deg, rgba(255, 69, 0, 0.8), rgba(255, 165, 0, 0.8));
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background 0.3s, box-shadow 0.3s;
        }

        .back-btn:hover {
            background: linear-gradient(135deg, rgba(255, 165, 0, 0.8), rgba(255, 69, 0, 0.8));
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        /* Error Message Styling */
        p {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 95%;
                padding: 20px;
            }

            h2 {
                font-size: 1.8rem;
            }

            form textarea {
                height: 100px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Announcement</h2>
        <form method="POST" action="edit_announcement.php?announcement_id=<?php echo $announcement_id; ?>">
            <textarea name="message" rows="5" required><?php echo htmlspecialchars($announcement['message']); ?></textarea>
            <button type="submit">Update Announcement</button>
            <?php if (isset($error)) echo "<p>$error</p>"; ?>
        </form>
        <a href="dashboard_admin.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
