<?php

include 'functions.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$con = connection();
$user_id = $_SESSION['user_id'];

if (isset($_GET['action']) && isset($_GET['flashcard_id'])) {
    $action = $_GET['action'];
    $flashcard_id = $_GET['flashcard_id'];
    // Define the update value based on the action
    $updateValue = ($action == 'like') ? 1 : -1;
    
    // Update the mastery field only for the current user and flashcard
    $query = "UPDATE user_performance SET mastery = GREATEST(LEAST(mastery + $updateValue, 5), 1), next_appearance = NOW() + INTERVAL 60 * (1+mastery) SECOND WHERE user_id = $user_id AND flashcard_id = $flashcard_id";
    mysqli_query($con, $query);
}

mysqli_close($con);
?>
