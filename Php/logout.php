<?php
session_start();
session_unset(); // remove session variables
session_destroy();
header("Location: ../index.php"); // redirect to home page
exit();
?>