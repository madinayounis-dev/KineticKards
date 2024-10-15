<?php
// Start session to enable the use of session variables
session_start();

// Include external functions file
include 'functions.php';

// Log error (assuming log() function is defined in functions.php)
log("error");

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Establish database connection
    $con = connection();

    // Get data from AJAX request
    $userId = $_POST['userId'];
    $flashcardId = $_POST['flashcardId'];
    $reaction = $_POST['reaction'];

    // Insert data into user_performance table
    $stmt = $con->prepare("INSERT INTO users.user_performance (user_id, flashcard_id, reaction) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $userId, $flashcardId, $reaction); // Bind parameters to the prepared statement
    $stmt->execute(); // Execute the prepared statement
    mysqli_close($con); // Close the database connection
}

?>
 