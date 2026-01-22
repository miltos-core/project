<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "metropolitan_db";

$conn = new mysqli($servername, $username, $password);
$msg = "";

/* Check connection */
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* Create database */
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

/* Creatign Users table*/
$conn->query("
CREATE TABLE IF NOT EXISTS Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL
)");

/* Adding demo Users */
$conn->query("
INSERT IGNORE INTO Users (id, username, email, password, role_id) VALUES
(1,'nikolaos_s','nikos@student.edu.gr','1234',1),
(2,'maria_p','maria@student.edu.gr','pass123',1),
(3,'konstadinos_k','kostas@student.edu.gr','student2025',1),
(4,'prof_georgos','geo@edu.gr','prof123',2),
(5,'prof_annastasia','anna@edu.gr','anna2025',2)
");

/* Creating Courses table*/
$conn->query("
CREATE TABLE IF NOT EXISTS Courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    professor_id INT,
    FOREIGN KEY (professor_id) REFERENCES Users(id)
)");

/* Adding Courses */
$conn->query("
INSERT IGNORE INTO Courses (id, title, description, professor_id) VALUES
(1,'Web Development #1','HTML, CSS, Js, PHP basics',4),
(2,'Databases','MySQL and data modeling',4),
(3,'Programming Language C++','C++, algorithms',5)
");

/* Creating Enrollments table */
$conn->query("
CREATE TABLE IF NOT EXISTS Enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    FOREIGN KEY (student_id) REFERENCES Users(id),
    FOREIGN KEY (course_id) REFERENCES Courses(id),
    UNIQUE(student_id, course_id)
)");

/* Adding Enrollments */
$conn->query("
INSERT IGNORE INTO Enrollments (student_id, course_id) VALUES
(1,1),(1,2),
(2,1),(2,3),
(3,2)
");

/*  Creating Assignments table */
$conn->query("
CREATE TABLE IF NOT EXISTS Assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    title VARCHAR(100),
    description TEXT,
    due_date DATE,
    FOREIGN KEY (course_id) REFERENCES Courses(id)
)");

/* Adding Assignments */
$conn->query("
INSERT IGNORE INTO Assignments (id, course_id, title, description, due_date) VALUES
(1,1,'HTML Website','Create a HTML website','2026-02-10'),
(2,2,'ER Diagram','Design a database ER diagram','2026-02-15'),
(3,3,'C++ Project','Create a Hello World project','2026-02-20')
");

/* Creating Submissions table */
$conn->query("
CREATE TABLE IF NOT EXISTS Submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assignment_id INT,
    student_id INT,
    file_name VARCHAR(255),
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (assignment_id) REFERENCES Assignments(id),
    FOREIGN KEY (student_id) REFERENCES Users(id)
)");

/* No demo submissions for testing uploads */

/* Creating Grades table */
$conn->query("
CREATE TABLE IF NOT EXISTS Grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    submission_id INT UNIQUE,
    grade VARCHAR(10),
    feedback TEXT,
    FOREIGN KEY (submission_id) REFERENCES Submissions(id)
)");

/* No demo grades for testing */


$msg = "System database and demo data ready!";
$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>System Setup</title>
        <link rel="stylesheet" href="../CSS/styles2.css">
    </head>
    <body>
    <div class="formDiv" style="text-align:center;">
        <h2>Setup Completed</h2>
        <p><?php echo $msg; ?></p>
        <a class="btn btn-submit" href="http://localhost/prototype/index.php">Go to Website</a>
    </div>
    </body>
</html>
