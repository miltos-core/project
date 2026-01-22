<?php

// Checks if the user is logged in as a student (role_id = 1).
// Starts the session and redirects to sign-in if not authorized.

function checkStudentAccess() {
    session_start();
    if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 1) {
        echo "<script>alert('Forbidden action! You do not have permission to access this page.'); window.location.href='../signIn.php';</script>";
        exit();
    }
}

// Checks if the user is logged in as a professor (role_id = 2).
// Starts the session and redirects to sign-in if not authorized. 

function checkProfessorAccess() {
    session_start();
    if (!isset($_SESSION['username']) || $_SESSION['role_id'] != 2) {
        echo "<script>alert('Forbidden action! You do not have permission to access this page.'); window.location.href='../signIn.php';</script>";
        exit();
    }
}
?>