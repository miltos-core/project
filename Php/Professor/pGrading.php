<?php
include '../includes/auth.php';
include '../includes/db.php';
checkProfessorAccess();

// Connect to database
$conn = getConnection();
$prof = $_SESSION['username'];

$prof_id = getUserId($prof);

/* Save grades */
if (isset($_POST['save_all'])) {
    foreach ($_POST['grade'] as $sid => $grade) {
        if (isset($_POST['feedback'][$sid])) {
            $feedback = $_POST['feedback'][$sid];
        } else {
            $feedback = '';
        }
        $conn->query("
            INSERT INTO Grades (submission_id, grade, feedback)
            VALUES ($sid, '$grade', '$feedback')
            ON DUPLICATE KEY UPDATE
                grade = '$grade',
                feedback = '$feedback'
        ");
    }
}

/* Get submissions for professor's courses */
$subs = $conn->query("
    SELECT s.id, u.username, a.title AS assignment, c.title AS course, g.grade, g.feedback
    FROM Submissions s
    JOIN Users u ON s.student_id = u.id
    JOIN Assignments a ON s.assignment_id = a.id
    JOIN Courses c ON a.course_id = c.id
    LEFT JOIN Grades g ON g.submission_id = s.id
    WHERE c.professor_id = $prof_id
");
?>

<?php
$pageTitle = "Grading";
$heading = "Grade Students";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Grading</title>
    <link rel="stylesheet" href="../../CSS/stylesMain.css">
</head>
<body class="user-page">
<div class="container">
<h2>Grade Students</h2>
<a href="../../dashboard.php" class="back-button">Back to Dashboard</a>

<!-- Table for grading student submissions -->
<form method="post">
<table border="1">
<tr>
    <th>Student</th>
    <th>Course</th>
    <th>Assignment</th>
    <th>Grade</th>
    <th>Feedback</th>
</tr>

<?php while ($s = $subs->fetch_assoc()): ?>
    <tr>
        <td><?php echo $s['username']; ?></td>
        <td><?php echo $s['course']; ?></td>
        <td><?php echo $s['assignment']; ?></td>
        <td>
            <input type="text" name="grade[<?php echo $s['id']; ?>]" value="<?php echo $s['grade']; ?>">
        </td>
        <td>
            <textarea name="feedback[<?php echo $s['id']; ?>]" rows="3" cols="30"><?php echo $s['feedback']; ?></textarea>
        </td>
    </tr>
<?php endwhile; ?>

</table>
<button type="submit" name="save_all">Save All Grades</button>
</form>

</div>
</body>
</html>
