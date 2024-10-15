<?php

// Function to check user login status
function check_login($con) {
    session_start(); // Start session if not already started
    if(isset($_SESSION['user_id'])) { // Check if user_id is set in session
        $id = $_SESSION['user_id']; // Get user_id from session
        $query = "SELECT * FROM dbusers WHERE user_id = $id LIMIT 1"; // SQL query to fetch user data from 'dbusers' table
        $result = mysqli_query($con, $query); // Execute query

        if($result && mysqli_num_rows($result) > 0) { // Check if query executed successfully and returned at least one row
            $user_data = mysqli_fetch_assoc($result); // Fetch user data as associative array
            return $user_data; // Return user data
        }
    }
    
    // Return null if user not found or session doesn't exist
    return null;
}

// Function to generate random number
function random_num($length) {
    $text = "";
    if($length < 5) { // If length is less than 5, set it to 5
        $length = 5;
    }

    for ($i = 0; $i < $length; $i++) { // Loop to generate random number
        $text .= rand(0,9); // Append random number to $text
    }

    return $text; // Return random number
}

// Function to establish database connection
function connection() {
    $dbhost = "localhost"; // Database host
    $dbuser = "root"; // Database username
    $dbpassword = ""; // Database password
    $dbname = "dbusers"; // Database name

    // Connect to the database
    $con = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);

    // Check connection
    if (!$con) { // If connection fails
        die("Connection failed: " . mysqli_connect_error()); // Print error message and terminate script
    }

    return $con; // Return database connection
}

?>
