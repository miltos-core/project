<?php
session_start();

// Sanitize user input to prevent unwanted events
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Connect to database
    $conn = new mysqli("localhost", "root", "", "metropolitan_db");
    
    // Check if connection succeeded
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Get and sanitize input from form
    $email = test_input($_POST['email']);
    $pass = test_input($_POST['password']);

    // Searching to find a user with matching email and password
    $stmt = $conn->prepare("SELECT id, username, role FROM Users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    // If a matching user is found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Store user information in session
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        
        echo "<script>
                alert('Success! Welcome back, " . $row['username'] . " (" . $row['role'] . ")');
                window.location.href='../index.php';
              </script>";
    } else {
        // Alert if email or password are incorrect
        echo "<script>alert('Invalid Username or Password. Please try again.');</script>";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <!-- Ensures proper layout scaling on mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta charset="UTF-8" />
    <title>Μητροπολιτικό Κολλέγιο Ρόδου - Sign In</title>
    <link rel="stylesheet" href="../CSS/styles2.css">
</head>
<body>

    <div class="nav">
        <a href="../index.php"><img src="../Images/Icon.png" id="icon" alt="Logo"></a>
        <div class="nav-right">
            <a href="signUp.php">Sign Up</a>
        </div>
    </div>

    <div class="formDiv">
        <form action="signIn.php" method="post">
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" class="form-input" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" class="form-input" name="password" required>
            </div>

            <div class="form-buttons">
                <input type="submit" class="btn btn-submit" value="Sign In">
                <input type="reset" class="btn btn-reset" value="Reset">
            </div>
            
        </form>
    </div>

</body>
</html>