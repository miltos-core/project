<?php
session_start();

// Block guests
if (!isset($_SESSION['username'])) {
    header("Location: php/signIn.php");
    exit();
}

$username = $_SESSION['username'];
$role_id = $_SESSION['role_id'];

if ($role_id == 1) {
    $role_name = 'Student';
} else {
    $role_name = 'Professor';
}
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
            <a><?php echo $username . " (" . $role_name . ")"; ?></a>
            <a href="php/logout.php">Logout</a>
        </div>
    </div>

    <div class="dash-container">

        <!-- Left Menu-->
        <div class="dash-menu">
            <h3>Dashboard</h3>

            <?php if ($role_id == 1): ?>
                <a href="php/student/sCourses.php">My Courses</a>
                <a href="php/student/sAssignments.php">My Assignments</a>
                <a href="php/student/sGrades.php">My Grades</a>
            <?php endif; ?>

            <?php if ($role_id == 2): ?>
                <a href="php/professor/pCourses.php">Manage Courses</a>
                <a href="php/professor/pAssignments.php">Post Assignments</a>
                <a href="php/professor/pGrading.php">Grade Students</a>
            <?php endif; ?>

        </div>

        <!-- Main Area -->
        <div class="dash-content">
            <h2>Welcome <?php echo $username; ?></h2>

            <?php if ($role_id == 1): ?>
                <p>You are logged in as a <strong>Student</strong>.  
                From here you can view your courses, submit assignments and check your grades.</p>
            <?php endif; ?>

            <?php if ($role_id == 2): ?>
                <p>You are logged in as a <strong>Professor</strong>.  
                From here you can manage courses, upload assignments and grade students.</p>
            <?php endif; ?>

        </div>

    </div>

    </body>
</html>
