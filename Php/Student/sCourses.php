<?php
include '../includes/auth.php';
include '../includes/db.php';
checkStudentAccess();

// Connect to database
$conn = getConnection();
$user = $_SESSION['username'];

$student_id = getUserId($user);

/* Fetch student courses */
$courses = $conn->query("
    SELECT Courses.title, Users.username AS professor
    FROM Enrollments
    JOIN Courses ON Enrollments.course_id = Courses.id
    JOIN Users ON Courses.professor_id = Users.id
    WHERE Enrollments.student_id = $student_id
");
?>

$pageTitle = "My Courses";
$heading = "My Courses";
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="../../CSS/stylesMain.css">
</head>
<body class="user-page">
<div class="container">
<h2><?php echo $heading; ?></h2>
<a href="../../dashboard.php" class="back-button">Back to Dashboard</a>

<!-- Table displaying student's enrolled courses -->
<table border="1">
<tr>
    <th>Course</th>
    <th>Professor</th>
</tr>

<?php while ($c = $courses->fetch_assoc()): ?>
    <tr>
        <td><?php echo $c['title']; ?></td>
        <td><?php echo $c['professor']; ?></td>
    </tr>
<?php endwhile; ?>

</table>

</div>
</body>
</html>
