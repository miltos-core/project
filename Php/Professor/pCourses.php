<?php
// Start session and check if user is logged in as professor
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 2) {
    echo "<script>alert('Forbidden action! You do not have permission to access this page.'); window.location.href='../signIn.php';</script>";
    exit();
}

// Connect to database
$conn = new mysqli("localhost","root","","metropolitan_db");
$user = $_SESSION['username'];

// Get professor id
$res = $conn->query("SELECT id FROM Users WHERE username='$user'");
$prof_id = $res->fetch_assoc()['id'];

// Create new course
if (isset($_POST['new_course'])) {
    $title = $_POST['new_course'];
    $conn->query("INSERT INTO Courses (title, professor_id) VALUES ('$title', $prof_id)");
}

// Enroll student
if (isset($_POST['student_id']) && isset($_POST['course_id'])) {
    $sid = $_POST['student_id'];
    $cid = $_POST['course_id'];
    $conn->query("INSERT IGNORE INTO Enrollments (student_id, course_id) VALUES ($sid, $cid)");
}

// Delete course
if (isset($_POST['delete_course'])) {
    $cid = $_POST['delete_course'];
    // First delete related enrollments and assignments
    $conn->query("DELETE FROM Enrollments WHERE course_id = $cid");
    $conn->query("DELETE FROM Assignments WHERE course_id = $cid");
    // Then delete the course
    $conn->query("DELETE FROM Courses WHERE id = $cid AND professor_id = $prof_id");
}

// Fetch professor courses
$courses = $conn->query("SELECT * FROM Courses WHERE professor_id = $prof_id");

// Fetch all students
$students = $conn->query("SELECT id, username FROM Users WHERE role_id=1");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Courses</title>
<link rel="stylesheet" href="../../CSS/stylesMain.css">
</head>
<body class="user-page">
<div class="container">

<h2>My Courses</h2>
<a href="../../dashboard.php" class="back-button">Back to Dashboard</a>

<!-- Form to create new course -->
<form method="post">
    <input type="text" name="new_course" placeholder="New course title" required>
    <button type="submit">Create Course</button>
</form>

<br>

<!-- Table displaying professor's courses -->
<table border="1">
<tr><th>ID</th><th>Course</th><th>Actions</th></tr>
<?php 
$courses->data_seek(0); // Reset result pointer
while($c = $courses->fetch_assoc()): 
?>
<tr>
    <td><?= $c['id'] ?></td>
    <td><?= $c['title'] ?></td>
    <td>
        <form method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this course? This will also remove all enrollments and assignments for this course.')">
            <input type="hidden" name="delete_course" value="<?= $c['id'] ?>">
            <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer;">Delete</button>
        </form>
    </td>
</tr>
<?php endwhile; ?>
</table>

<hr>

<h3>Enroll Student</h3>

<!-- Form to enroll students in courses -->
<form method="post">
    <select name="student_id" required>
        <option value="">Select Student</option>
        <?php while($s = $students->fetch_assoc()): ?>
            <option value="<?= $s['id'] ?>"><?= $s['username'] ?></option>
        <?php endwhile; ?>
    </select>

    <select name="course_id" required>
        <option value="">Select Course</option>
        <?php
        $courses->data_seek(0); // Reset result pointer
        while($c = $courses->fetch_assoc()):
        ?>
            <option value="<?= $c['id'] ?>"><?= $c['title'] ?></option>
        <?php endwhile; ?>
    </select>

    <button type="submit">Enroll</button>
</form>

</div>
</body>
</html>
