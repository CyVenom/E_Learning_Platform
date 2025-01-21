<?php
session_start();
$conn = new mysqli("localhost", "root", "", "learning_platform");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle logout functionality
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Handle enroll/unenroll functionality
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id'])) {
    $course_id = intval($_POST['course_id']);

    if (isset($_POST['action']) && $_POST['action'] === "unenroll") {
        // Unenroll the student from the course
        $unenroll_query = "DELETE FROM enrollments WHERE student_id = $user_id AND course_id = $course_id";
        if ($conn->query($unenroll_query)) {
            $message = "You have successfully unenrolled from the course.";
        } else {
            $message = "Failed to unenroll from the course. Please try again.";
        }
    } else {
        // Enroll the student in the course
        $check_query = "SELECT * FROM enrollments WHERE student_id = $user_id AND course_id = $course_id";
        $check_result = $conn->query($check_query);

        if ($check_result->num_rows === 0) {
            $enroll_query = "INSERT INTO enrollments (student_id, course_id) VALUES ($user_id, $course_id)";
            if ($conn->query($enroll_query)) {
                $message = "You have successfully enrolled in the course!";
            } else {
                $message = "Failed to enroll in the course. Please try again.";
            }
        } else {
            $message = "You are already enrolled in this course.";
        }
    }
}

// Fetch all courses with teacher names and student enrollment status
$query = "
    SELECT 
        courses.id AS course_id,
        courses.title,
        courses.description,
        users.name AS teacher_name,
        (SELECT COUNT(*) FROM enrollments WHERE enrollments.course_id = courses.id) AS total_students,
        (SELECT COUNT(*) FROM enrollments WHERE enrollments.course_id = courses.id AND enrollments.student_id = $user_id) AS is_enrolled
    FROM courses
    INNER JOIN users ON courses.teacher_id = users.id
";
$result = $conn->query($query);



$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $conn->real_escape_string($_GET['search']);
}

$query = "
    SELECT 
        courses.id AS course_id,
        courses.title,
        courses.description,
        users.name AS teacher_name,
        (SELECT COUNT(*) FROM enrollments WHERE enrollments.course_id = courses.id) AS total_students,
        (SELECT COUNT(*) FROM enrollments WHERE enrollments.course_id = courses.id AND enrollments.student_id = $user_id) AS is_enrolled
    FROM courses
    INNER JOIN users ON courses.teacher_id = users.id
    WHERE courses.title LIKE '%$search_query%' OR courses.description LIKE '%$search_query%'
";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles_student_dashboard.css">
    <title>Student Dashboard</title>
</head>
<body>
    <div class="container">
        <h2>Welcome to the Student Dashboard</h2>

        <?php if (isset($message)): ?>
            <div class="message">
                <p><?php echo $message; ?></p>
            </div>
        <?php endif; ?>

        <!-- Search Bar -->
        <form method="GET" action="dashboard_student.php">
            <input type="text" name="search" placeholder="Search courses..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit">Search</button>
        </form>

        <h3>Available Courses</h3>
        <ul>
            <?php while ($course = $result->fetch_assoc()) { ?>
                <li>
                    <strong><?php echo $course['title']; ?></strong> - <?php echo $course['description']; ?><br>
                    <em>Instructor:</em> <?php echo $course['teacher_name']; ?><br>
                    <em>Enrolled Students:</em> <?php echo $course['total_students']; ?><br>
                    <?php if ($course['is_enrolled'] > 0) { ?>
                        <form method="POST" action="dashboard_student.php" style="display:inline;">
                            <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
                            <input type="hidden" name="action" value="unenroll">
                            <button type="submit">Unenroll</button>
                        </form>
                    <?php } else { ?>
                        <form method="POST" action="dashboard_student.php" style="display:inline;">
                            <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
                            <button type="submit">Enroll</button>
                        </form>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
        <!-- Logout Button -->
        <a href="dashboard_student.php?logout=true" class="logout-btn">Logout</a>
    </div>
</body>
</html>

