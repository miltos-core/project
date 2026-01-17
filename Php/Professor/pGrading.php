<?php
// Start session and check if user is logged in as professor
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 2) {
    echo "<script>alert('Forbidden action! You do not have permission to access this page.'); window.location.href='../signIn.php';</script>";
    exit();
}

// Connect to database
$conn = new mysqli("localhost","root","","metropolitan_db");
$prof = $_SESSION['username'];

/* Professor ID */
$res = $conn->query("SELECT id FROM Users WHERE username='$prof'");
$prof_id = $res->fetch_assoc()['id'];

/* Save grade */
if(isset($_POST['submission_id'])){
    $grade = $_POST['grade'];
    $feedback = $_POST['feedback'];
    $sid = $_POST['submission_id'];

    $conn->query("
    INSERT INTO Grades (submission_id, grade, feedback)
    VALUES ($sid, '$grade', '$feedback')
    ON DUPLICATE KEY UPDATE grade='$grade', feedback='$feedback'
    ");
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
<table border="1">
<tr>
    <th>Student</th>
    <th>Course</th>
    <th>Assignment</th>
    <th>Grade</th>
    <th>Feedback</th>
    <th>Save</th>
</tr>

<?php while($s = $subs->fetch_assoc()): ?>
<tr>
<form method="post">
    <td><?= $s['username'] ?></td>
    <td><?= $s['course'] ?></td>
    <td><?= $s['assignment'] ?></td>
    <td>
        <input type="text" name="grade" value="<?= $s['grade'] ?>">
        <input type="hidden" name="submission_id" value="<?= $s['id'] ?>">
    </td>
    <td>
        <textarea name="feedback" rows="3" cols="30"><?= $s['feedback'] ?></textarea>
    </td>
    <td><button>Save</button></td>
</form>
</tr>
<?php endwhile; ?>

</table>

</div>
</body>
</html>
