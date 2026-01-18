<?php
include '../includes/auth.php';
include '../includes/db.php';
checkStudentAccess();

// Connect to database
$conn = getConnection();
$user = $_SESSION['username'];

$student_id = getUserId($user);

/* Fetch grades */
$grades = $conn->query("
    SELECT c.title AS course, a.title AS assignment, g.grade
    FROM Submissions s
    JOIN Assignments a ON s.assignment_id = a.id
    JOIN Courses c ON a.course_id = c.id
    LEFT JOIN Grades g ON g.submission_id = s.id
    WHERE s.student_id = $student_id
");

$pageTitle = "My Grades";
$heading = "My Grades";
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

<!-- Table displaying student's grades -->
<table border="1">
<tr>
    <th>Course</th>
    <th>Assignment</th>
    <th>Grade</th>
</tr>

<?php while ($g = $grades->fetch_assoc()): ?>
    <tr>
        <td><?php echo $g['course']; ?></td>
        <td><?php echo $g['assignment']; ?></td>
        <td><?php echo $g['grade']; ?></td>
    </tr>
<?php endwhile; ?>

</table>

</div>
</body>
</html>
