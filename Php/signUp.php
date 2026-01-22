<?php

// Sanitize user input to prevent unwanted events
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
    // After getting the data using POST method , it gets sanitized and stored
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = test_input($_POST['username']);
    $email = test_input($_POST['email']);
    $pass = test_input($_POST['password']);
    $role = test_input($_POST['type']);
    $code = test_input($_POST['code']);
    if ($role == "student") {
        $role_id = 1;
    } else {
        $role_id = 2;
    }
    
    $can_register = false;

    // Checking if one of the requirements are met to start the registration
    if ($role == "professor" && $code == "PROF2025") {
        $can_register = true;
    } elseif ($role == "student" && $code == "STUD2025") {
        $can_register = true;
    }
    // Check if connection succeeded
    if ($can_register) {
        // Connect to database
        $conn = new mysqli("localhost", "root", "", "metropolitan_db");
        
        
         if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }
        // Check if email and username already exist
        $check = $conn->query("SELECT * FROM Users WHERE username='$user' OR email='$email'");

        if ($check->num_rows > 0) {
            echo "<script>alert('Username or Email already taken!'); window.history.back();</script>";
        } else {
            // insert new user into database
            $sql = "INSERT INTO Users (username, email, password, role_id) VALUES ('$user', '$email', '$pass', $role_id)";
            
            if ($conn->query($sql) === TRUE) {
            // Success message using js window popup and redirect to sign in
            echo "<script>alert('Account created successfully for $user!'); window.location.href='signIn.php';</script>";
            } else {
            echo "Error: " . $conn->error;
            }
        }
        $conn->close();
    } else {
        // Wrong registration code alert
        echo "<script>alert('Wrong Registration Code!'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="el">
    <head>
        <!-- Ensures proper layout scaling on mobile devices -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <meta charset="UTF-8">
        <title>Μητροπολιτικό Κολλέγιο Ρόδου - Sign Up</title>
        <link rel="stylesheet" href="../CSS/styles2.css">
    </head>
    <body>

        <div class="nav">
            <a href="../index.php"><img src="../Images/Icon.png" id="icon" alt="Logo"></a>
            <div class="nav-right">
                <a href="signIn.php">Sign In</a>
            </div>
        </div>

        <div class="formDiv">
            <form action="signUp.php" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" class="form-input" name="username" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" class="form-input" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" class="form-input" name="password" required>
                </div>

                <div class="form-group">
                    <label for="code">Registration Code:</label>
                    <input type="text" name="code" id="code" class="form-input" required placeholder="PROF2025 or STUD2025">
                </div>

                <div class="form-group">
                    <label>User Type:</label>
                    <input type="radio" class="form-radio" id="professor" name="type" value="professor" required>
                    <label for="professor">Professor</label>
                    <input type="radio" class="form-radio" id="student" name="type" value="student" required>
                    <label for="student">Student</label>
                </div>

                <div class="form-buttons">
                    <input type="submit" class="btn btn-submit" value="Sign Up">
                    <input type="reset" class="btn btn-reset" value="Reset">
                </div>
            </form>
        </div>

    </body>
</html>