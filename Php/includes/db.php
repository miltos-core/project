<?php

// Establishes and returns a new database connection to the MySQL server.
function getConnection() {
    return new mysqli("localhost", "root", "", "metropolitan_db");
}

// Retrieves the user ID from the database based on the username. 
function getUserId($username) {
    $conn = getConnection();
    $res = $conn->query("SELECT id FROM Users WHERE username='$username'");
    return $res->fetch_assoc()['id'];
}
?>