<?php
// Start session and check if user is logged in as student
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 1) {
    echo "<script>alert('Forbidden action! You do not have permission to access this page.'); window.location.href='../signIn.php';</script>";
    exit();
}

// Connect to database
$conn = new mysqli("localhost","root","","metropolitan_db");
$user = $_SESSION['username'];

/* Get student id */
$res = $conn->query("SELECT id FROM Users WHERE username='$user'");
$student_id = $res->fetch_assoc()['id'];

/* Fetch student courses */
$courses = $conn->query("
    SELECT Courses.title, Users.username AS professor
    FROM Enrollments
    JOIN Courses ON Enrollments.course_id = Courses.id
    JOIN Users ON Courses.professor_id = Users.id
    WHERE Enrollments.student_id = $student_id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Courses</title>
    <link rel="stylesheet" href="../../CSS/stylesMain.css">
</head>
<body class="user-page">
<div class="container">

<h2>My Courses</h2>
<a href="../../dashboard.php" class="back-button">Back to Dashboard</a>

<!-- Table displaying student's enrolled courses -->
<table border="1">
<tr>
    <th>Course</th>
    <th>Professor</th>
</tr>

<?php while($c = $courses->fetch_assoc()): ?>
<tr>
    <td><?= $c['title'] ?></td>
    <td><?= $c['professor'] ?></td>
</tr>
<?php endwhile; ?>

</table>

</div>
</body>
</html>
