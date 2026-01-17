<?php
// Start session and check if user is logged in as student
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== "student") {
    header("Location: ../signIn.php");
    exit();
}

// Connect to database
$conn = new mysqli("localhost","root","","metropolitan_db");
$user = $_SESSION['username'];

/* Get student ID */
$res = $conn->query("SELECT id FROM Users WHERE username='$user'");
$student_id = $res->fetch_assoc()['id'];

/* Fetch grades */
$grades = $conn->query("
    SELECT c.title AS course, a.title AS assignment, g.grade
    FROM Submissions s
    JOIN Assignments a ON s.assignment_id = a.id
    JOIN Courses c ON a.course_id = c.id
    LEFT JOIN Grades g ON g.submission_id = s.id
    WHERE s.student_id = $student_id
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>My Grades</title>
    <link rel="stylesheet" href="../../CSS/stylesMain.css">
</head>
<body class="user-page">
<div class="container">

<h2>My Grades</h2>
<a href="../../dashboard.php" class="back-button">Back to Dashboard</a>

<!-- Table displaying student's grades -->
<table border="1">
<tr>
    <th>Course</th>
    <th>Assignment</th>
    <th>Grade</th>
</tr>

<?php while($g = $grades->fetch_assoc()): ?>
<tr>
    <td><?= $g['course'] ?></td>
    <td><?= $g['assignment'] ?></td>
    <td><?= $g['grade'] ?></td>
</tr>
<?php endwhile; ?>

</table>

</div>
</body>
</html>
