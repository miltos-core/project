<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== "professor") {
    header("Location: ../signIn.php");
    exit();
}

$conn = new mysqli("localhost","root","","metropolitan_db");
$prof = $_SESSION['username'];

/* Get professor ID */
$res = $conn->query("SELECT id FROM Users WHERE username='$prof'");
$prof_id = $res->fetch_assoc()['id'];

/* Handle new assignment */
if(isset($_POST['title'])){
    $title = $_POST['title'];
    $date = $_POST['due'];
    $course = $_POST['course'];

    $conn->query("INSERT INTO Assignments (title, due_date, course_id)
                  VALUES ('$title','$date',$course)");
}

/* Get professor's courses */
$courses = $conn->query("SELECT * FROM Courses WHERE professor_id=$prof_id");

/* Get all assignments */
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
<body class="user-page">

<h2>Post New Assignment</h2>

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

</body>
</html>
