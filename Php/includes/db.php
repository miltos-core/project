<?php

// Establishes and returns a new database connection to the MySQL server.
function getConnection() {
    return new mysqli("localhost", "root", "", "metropolitan_db");
}

// Retrieves the user ID from the database based on the username.
function getUserId($username) {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT id FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row ? $row['id'] : null;
}
?>