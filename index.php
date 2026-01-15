<?php

session_start();
$display_name = "";

// Checks if the user Is signed in and puts his name in a variable to use later on line 30
if(isset($_SESSION['username'])) {
    $display_name = "Welcome, " . $_SESSION['username'] . " (" . $_SESSION['role'] . ")";
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <!-- Ensures proper layout scaling on mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta charset="UTF-8" />
    <title>Μητροπολιτικό Κολλέγιο Ρόδου</title>
    <link rel="stylesheet" href="/prototype/CSS/stylesMain.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>

<body>

    <div class="nav">
        <a href="index.php">
            <img src="IMAGES/Icon.png" id="icon">
        </a>
        <div class="nav-right">

            <!-- If the user is signed in, replace Sign Up / Sign In links with the user's name and a Logout link -->
            <?php if(isset($_SESSION['username'])): ?> 
                <a><?php echo $display_name; ?></a>
                <a href="dashboard.php">Dashboard</a>
                <a href="php/logout.php">Logout</a>
            <?php else: ?>
                <a href="php/signUp">Sign Up</a>
                <a href="php/signIn">Sign In</a>
            <?php endif; ?>
        
        </div>
    </div>

    <!-- Hero section with the main capus image and the title -->
    <div class="hero">
        <img src="Images/firstimage.jpg" alt="Campus Reception Area" class="img1">
        <h1 class="titleText">Μητροπολιτικό Κολλέγιο Ρόδου</h1>
    </div>

    <!-- Blue content section with the campus description and image -->
    <div class="content blue">
        <img src="Images/campus-rodos-1.jpg" alt="Campus Entrance" class="img2">
        <p>
            Το Campus του Μητροπολιτικού Κολλεγίου Ρόδου συνδυάζει σύγχρονες εγκαταστάσεις με έναν φιλικό και υποστηρικτικό μαθησιακό περιβάλλον.
            Οι φοιτητές έχουν πρόσβαση σε πλήρως εξοπλισμένα εργαστήρια, βιβλιοθήκες, αίθουσες διδασκαλίας και χώρους συνεργασίας.
            Στόχος μας είναι να ενθαρρύνουμε τη δημιουργικότητα, την καινοτομία και την πρακτική εφαρμογή γνώσεων σε πραγματικά έργα.
        </p>
    </div>
    
    <!-- Red content section with the campus description and image -->
    <div class="content red">
        <img src="Images/campus-rodos-2.jpg" alt="Campus Classroom" class="img2">
        <p>
            Προσφέρουμε προγράμματα σπουδών σε τεχνολογία, επιχειρήσεις και εφαρμοσμένες τέχνες, με έμφαση στην πρακτική εμπειρία και την ανάπτυξη δεξιοτήτων που απαιτούνται στην αγορά εργασίας.
            Το κολέγιο συνεργάζεται με τοπικές επιχειρήσεις και φορείς, δίνοντας στους φοιτητές τη δυνατότητα συμμετοχής σε internships και καινοτόμα projects.
            Οι καθηγητές μας είναι έμπειροι επαγγελματίες που υποστηρίζουν τους φοιτητές σε κάθε βήμα της εκπαίδευσής τους.
        </p>
    </div>
    
    <!-- Leaflet.js map setup -->
    <div id="map"></div>
    <script src="Js/script.js"></script>
    
</body>
</html>