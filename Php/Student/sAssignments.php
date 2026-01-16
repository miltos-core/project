<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== "student") {
    header("Location: ../signIn.php");
    exit();
}

$conn = new mysqli("localhost","root","","metropolitan_db");
$student = $_SESSION['username'];

/* Get student ID */
$res = $conn->query("SELECT id FROM Users WHERE username='$student'");
$student_id = $res->fetch_assoc()['id'];

/* Get assignments for enrolled courses */
$sql = "
SELECT 
    Assignments.id,
    Assignments.title,
    Assignments.due_date,
    Courses.title AS course,
    Submissions.file_name
FROM Enrollments
JOIN Courses ON Enrollments.course_id = Courses.id
JOIN Assignments ON Assignments.course_id = Courses.id
LEFT JOIN Submissions 
    ON Submissions.assignment_id = Assignments.id 
    AND Submissions.student_id = $student_id
WHERE Enrollments.student_id = $student_id
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Assignments</title>
    <link rel="stylesheet" href="../../CSS/stylesMain.css">
<body class="user-page">

<h2>My Assignments</h2>

<table>
<tr>
    <th>Course</th>
    <th>Assignment</th>
    <th>Due Date</th>
    <th>Status</th>
</tr>


<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['course'] ?></td>
    <td><?= $row['title'] ?></td>
    <td><?= $row['due_date'] ?></td>
    <td>
        <?php if($row['file_name']): ?>
            Submitted (<?= $row['file_name'] ?>)
        <?php else: ?>
            Not submitted
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>
