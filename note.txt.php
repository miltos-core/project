<?php
session_start();

/* Not logged in */
if (!isset($_SESSION['username'])) {
    header("Location: ../php/signIn.php");
    exit();
}

/* Page defines which role is allowed */
if (!isset($requiredRole)) {
    die("Security misconfiguration.");
}

/* If wrong role */
if ($_SESSION['role'] !== $requiredRole) {
    echo "<h2 style='color:red;text-align:center;margin-top:100px;'>Forbidden Action</h2>";
    exit();
}
?>

For student pages:
<?php
$requiredRole = "student";
require "../php/rbac.php";
?>

For professor pages:
<?php
$requiredRole = "professor";
require "../php/rbac.php";
?>