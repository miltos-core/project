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

/* Get professor ID */
$res = $conn->query("SELECT id FROM Users WHERE username='$prof'");
$prof_id = $res->fetch_assoc()['id'];

/* Handle new assignment creation */
if(isset($_POST['title'])){
    $title = $_POST['title'];
    $date = $_POST['due'];
    $course = $_POST['course'];

    $conn->query("INSERT INTO Assignments (title, due_date, course_id)
                  VALUES ('$title','$date',$course)");
}

/* Get professor's courses for dropdown */
$courses = $conn->query("SELECT * FROM Courses WHERE professor_id=$prof_id");

/* Get all assignments for this professor */
$assignments = $conn->query("
    SELECT Assignments.title, Assignments.due_date, Courses.title AS course
    FROM Assignments
    JOIN Courses ON Assignments.course_id = Courses.id
    WHERE Courses.professor_id = $prof_id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Post Assignments</title>
    <link rel="stylesheet" href="../../CSS/stylesMain.css">
</head>
<body class="user-page">
<div class="container">

<h2>Post New Assignment</h2>
<a href="../../dashboard.php" class="back-button">Back to Dashboard</a>

<!-- Form to create new assignment -->
<form method="post">
    <input type="text" name="title" placeholder="Assignment title" required>
    <input type="date" name="due" required>

    <select name="course" required>
        <?php while($c = $courses->fetch_assoc()): ?>
            <option value="<?= $c['id'] ?>"><?= $c['title'] ?></option>
        <?php endwhile; ?>
    </select>

    <button type="submit">Post</button>
</form>

<hr>

<h2>Your Assignments</h2>
<!-- Table displaying professor's assignments -->
<table>
<tr><th>Course</th><th>Title</th><th>Due Date</th></tr>

<?php while($a = $assignments->fetch_assoc()): ?>
<tr>
    <td><?= $a['course'] ?></td>
    <td><?= $a['title'] ?></td>
    <td><?= $a['due_date'] ?></td>
</tr>
<?php endwhile; ?>

</table>

<hr>

<h2>Submissions</h2>
<!-- Table displaying student submissions for professor's assignments -->
<?php
$subs = $conn->query("
    SELECT Assignments.title AS assignment, Courses.title AS course, Users.username AS student, Submissions.file_name
    FROM Submissions
    JOIN Assignments ON Submissions.assignment_id = Assignments.id
    JOIN Courses ON Assignments.course_id = Courses.id
    JOIN Users ON Submissions.student_id = Users.id
    WHERE Courses.professor_id = $prof_id
");
?>
<table>
<tr><th>Course</th><th>Assignment</th><th>Student</th><th>File</th></tr>

<?php while($s = $subs->fetch_assoc()): ?>
<tr>
    <td><?= $s['course'] ?></td>
    <td><?= $s['assignment'] ?></td>
    <td><?= $s['student'] ?></td>
    <td><a href="../../SubmitedAssignments/<?= $s['file_name'] ?>" download>Download</a></td>
</tr>
<?php endwhile; ?>

</table>

</div>
</body>
</html>
