<?php
include '../includes/auth.php';
include '../includes/db.php';
checkStudentAccess();

// Connect to database
$conn = getConnection();
$student = $_SESSION['username'];

$student_id = getUserId($student);

/* Handle file upload */
if(isset($_POST['upload'])){
    $assignment_id = $_POST['assignment_id'];
    $file_name = basename($_FILES['file']['name']);
    $target = "../../SubmitedAssignments/" . $file_name;
    if(move_uploaded_file($_FILES['file']['tmp_name'], $target)){
        $conn->query("INSERT INTO Submissions (assignment_id, student_id, file_name) VALUES ($assignment_id, $student_id, '$file_name')");
        echo "<script>alert('Upload successful.');</script>";
    } else {
        echo "<script>alert('Upload failed.');</script>";
    }
}

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

<?php
$pageTitle = "My Assignments";
$heading = "My Assignments";
?>

<!DOCTYPE html>
<html>
    <head>
        <title>My Assignments</title>
        <link rel="stylesheet" href="../../CSS/stylesMain.css">
    </head>
    
    <body class="user-page">
    <div class="container">
    <h2>My Assignments</h2>
    <a href="../../dashboard.php" class="back-button">Back to Dashboard</a>

    <!-- Table displaying student's assignments with upload option -->
    <table>
    <tr>
        <th>Course</th>
        <th>Assignment</th>
        <th>Due Date</th>
        <th>Status</th>
    </tr>


    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['course']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['due_date']; ?></td>
            <td>
                <?php if ($row['file_name']): ?>
                    Submitted (<?php echo $row['file_name']; ?>)
                <?php else: ?>
                    <!-- Upload form for unsubmitted assignments -->
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="assignment_id" value="<?php echo $row['id']; ?>">
                        <input type="file" name="file" required>
                        <button type="submit" name="upload">Upload</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>

    </table>

    </div>
    </body>
</html>
