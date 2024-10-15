<?php
// Display all PHP errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include functions.php file which contains necessary functions
include("functions.php");

// Start session
session_start();

// Establish database connection
$con = connection();

// Initialize error message variable
$error_message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Retrieve form data
    $user_name = $_POST['Username'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    $confirm_password = $_POST['ConfirmPassword'];

    // Check if username or email already exists in the database
    $check_query = "SELECT * FROM dbusers WHERE user_name = '$user_name' OR email = '$email'";
    $check_result = mysqli_query($con, $check_query);

    // If username or email already exists, set error message
    if (mysqli_num_rows($check_result) > 0) {
        $error_message = "Username or email already exists.";
    } else {
        // Validate form inputs
        if (!empty($user_name) && !empty($email) && !empty($password) && !empty($confirm_password) && !is_numeric($user_name) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Check if passwords match
            if ($password === $confirm_password) {
                // Validate password strength
                if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
                    // Hash the password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    // Insert user data into database
                    $query = "INSERT INTO dbusers (user_id, user_name, email, password) VALUES ('$user_id', '$user_name', '$email', '$hashed_password')";
                    $result = mysqli_query($con, $query);

                    // If registration is successful, redirect to login page
                    if ($result) {
                        header("Location: login.php");
                        exit();
                    } else {
                        // If unable to register, set error message
                        $error_message = "Error: Unable to register. Please try again later.";
                    }
                } else {
                    // If password doesn't meet criteria, set error message
                    $error_message = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.";
                }
            } else {
                // If passwords don't match, set error message
                $error_message = "Passwords do not match.";
            }
        } else {
            // If form fields are not filled correctly, set error message
            $error_message = "Please fill all fields correctly.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KineticKards Sign Up</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../styles/navbar-style.css">
    <link rel="stylesheet" href="../styles/signup-styles.css">
    <link rel="stylesheet" href="../styles/login-styles.css">

</head>
<nav class="navbar">
    <div class="navbar__container">
        <div class="navbar__logo">
        <!-- Left side of the navbar -->
        <img src="" alt="KineticKards" style="margin-right:60px; color:#856ef6">  <!-- Adds a margin to the right and change the colour of the text -->
            

            <a href="#" class="navbar__link">Your Library</a>
            <a href="#" class="navbar__link">Study Tools</a>
        </div>

        <!-- Searchbar on navbar -->
        <div class="navbar__search">
            <input type="text" placeholder="Search...">
            <span class="search-icon">
        
                <i class='bx bx-search'></i> <!-- Searchbar Icon -->
            </span>
        </div>

        <!-- Navbar Add Icon -->
        <div class="navbar__actions">
                <a href="#" class="navbar__action" style="display: flex; align-items: center; justify-content: center; min-width: 34px; width: 34px; height: 34px; border: 2px solid #9f91e5; border-radius: 50%; margin-right: 15px; padding: 2px; text-decoration: none;">
            <i class='bx bx-plus' style="font-size: 30px; color: #9f91e5; transform:translatex(-5px);"></i>

        </a>

        <!-- Navbar notification icon -->
        <a href="#" class="navbar__action" style="display: flex; align-items: center; justify-content: center; min-width: 34px; width: 34px; height: 34px; border: 2px solid #9f91e5; border-radius: 50%; margin-right: 15px; padding: 2px; text-decoration: none;">
            <i class='bx bx-bell' style="font-size: 28px; color: #9f91e5; transform:translatex(-4px);"></i>
        </a>

        <!-- Profile Picture -->
        <a href="#" class="navbar__action"><img src="../styles/pfp.png" alt="Profile" style="height: 46px; width: 46px; border-radius: 50%; transform:translatey(2px)"></a>

        <!-- Upgrade Button -->
        <a href="#" class="navbar__action upgrade">Upgrade Today</a>
        </div>
    </div>
</nav>
<body>

<div class="content-wrapper">
    <div class="wrapper">
        <!-- Signup form -->
        <form class="signup-form" method="post">
            <h1>Sign Up to KineticKards Today!</h1>
            <?php if (!empty($error_message)): ?>
                <!-- Display error message if any -->
                <p style="color: red;"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <!-- Username input -->
            <div class="input-box">
                <input type="text" name="Username" placeholder="Enter your chosen username" /><br />
                <!-- Icon for username -->
                <i class='bx bxs-user'></i>
            </div>
            <!-- Email input -->
            <div class="input-box">
                <input type="email" name="Email" placeholder="Enter your email" /><br />
                <!-- Icon for email -->
                <i class='bx bxs-envelope'></i>
            </div>
            <!-- Password input -->
            <div class="input-box">
                <input type="password" name="Password" id="password" placeholder="Enter your chosen password" />
                <!-- Icon for password and toggle button to show/hide password -->
                <i class='bx bxs-show password-toggle-btn' onclick="togglePasswordVisibility('password', 'confirmPassword', this)"></i>
            </div>
            <!-- Confirm Password input -->
            <div class="input-box">
                <input type="password" name="ConfirmPassword" id="confirmPassword" placeholder="Re-type your password" />
            </div>

            <!-- Terms and Conditions -->
            <p>By creating an account you agree to our <a href="#">Terms and Conditions</a></p>
            <!-- Signup button -->
            <button class="registerbtn" type="submit">Sign Up</button>

            <!-- Login link for existing users -->
            <div class="register-link">
                <p>Already have an account? <a href="../LoginPage/login.php"> Login </a></p>
            </div>
        </form>
    </div>
</div>
<script>
    // Function to toggle visibility of passwords
    function togglePasswordVisibility(fieldId1, fieldId2, icon) {
        // Get references to the password input fields and the icon
        var field1 = document.getElementById(fieldId1);
        var field2 = document.getElementById(fieldId2);
        // Get the current type of the input fields (password or text)
        var fieldType1 = field1.getAttribute('type');
        var fieldType2 = field2.getAttribute('type');

        // If both fields are currently set to password, change them to text
        if (fieldType1 === "password" && fieldType2 === "password") {
            field1.setAttribute('type', 'text');
            field2.setAttribute('type', 'text');
            // Change the icon to indicate hidden passwords
            icon.classList.remove('bxs-show');
            icon.classList.add('bxs-hide');
        } else {
            // Otherwise, change them back to password
            field1.setAttribute('type', 'password');
            field2.setAttribute('type', 'password');
            // Change the icon to indicate visible passwords
            icon.classList.remove('bxs-hide');
            icon.classList.add('bxs-show');
        }
    }
</script>



</body>
<footer>
    <p>&copy; 2024 KineticKards</p>
</footer>
</html>