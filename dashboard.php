<?php
session_start();

/* Block guests */
if (!isset($_SESSION['username'])) {
    header("Location: php/signIn.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role']; // "student" or "professor"
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="CSS/stylesMain.css">
</head>
<body>

<div class="nav">
    <a href="index.php"><img src="Images/Icon.png" id="icon"></a>
    <div class="nav-right">
        <a><?php echo $username . " (" . $role . ")"; ?></a>
        <a href="php/logout.php">Logout</a>
    </div>
</div>

<div class="dash-container">

    <!-- LEFT MENU -->
    <div class="dash-menu">
        <h3>Dashboard</h3>

        <?php if ($role === "student"): ?>
            <a href="php/student/sCourses.php">My Courses</a>
            <a href="php/student/sAssignments.php">My Assignments</a>
            <a href="php/student/sGrades.php">My Grades</a>
        <?php endif; ?>

        <?php if ($role === "professor"): ?>
            <a href="php/professor/pCourses.php">Manage Courses</a>
            <a href="php/professor/pAssignments.php">Post Assignments</a>
            <a href="php/professor/pGrading.php">Grade Students</a>
        <?php endif; ?>

    </div>

    <!-- MAIN AREA -->
    <div class="dash-content">
        <h2>Welcome <?php echo $username; ?></h2>

        <?php if ($role === "student"): ?>
            <p>You are logged in as a <strong>Student</strong>.  
            From here you can view your courses, submit assignments and check your grades.</p>
        <?php endif; ?>

        <?php if ($role === "professor"): ?>
            <p>You are logged in as a <strong>Professor</strong>.  
            From here you can manage courses, upload assignments and grade students.</p>
        <?php endif; ?>

    </div>

</div>

</body>
</html>
