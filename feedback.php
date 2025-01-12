<?php
session_start();
$conn = new mysqli("localhost", "root", "", "learning_platform");

// Ensure the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Current logged-in student ID

// Handle feedback submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = intval($_POST['course_id']);
    $comment = $conn->real_escape_string($_POST['comment']);

    $query = "INSERT INTO feedback (user_id, course_id, comment) VALUES ($user_id, $course_id, '$comment')";
    if ($conn->query($query)) {
        $message = "Feedback submitted successfully!";
    } else {
        $message = "Failed to submit feedback. Please try again.";
    }
}

// Fetch all courses the student is enrolled in
$courses_query = "
    SELECT courses.id, courses.title 
    FROM courses 
    INNER JOIN enrollments ON courses.id = enrollments.course_id 
    WHERE enrollments.student_id = $user_id
";
$courses = $conn->query($courses_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles_feedback.css">
    <title>Submit Feedback</title>
</head>
<body>
    <div class="container">
        <h2>Submit Feedback</h2>
        <?php if (isset($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" action="feedback.php">
            <label for="course_id">Select Course:</label>
            <select name="course_id" id="course_id" required>
                <?php while ($course = $courses->fetch_assoc()) { ?>
                    <option value="<?php echo $course['id']; ?>"><?php echo $course['title']; ?></option>
                <?php } ?>
            </select>
            <textarea name="comment" placeholder="Write your feedback here..." required></textarea>
            <button type="submit">Submit Feedback</button>
        </form>
    </div>
</body>
</html>
