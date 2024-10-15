<?php
// Including functions.php which contains reusable functions
include("functions.php");

// Starting a session to manage user sessions
session_start();

// Establishing a database connection
$con = connection();

// Initializing an error message variable
$error_message = '';

// Checking if the form has been submitted via POST method
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Retrieving username and password from the POST data
    $user_name = $_POST['username'];
    $password = $_POST['password'];

    // Validating input fields
    if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        // Querying the database to fetch user data based on the provided username
        $query = "SELECT * FROM dbusers WHERE user_name = '$user_name' LIMIT 1";
        $result = mysqli_query($con, $query);

        // Checking if the query executed successfully and returned a row
        if ($result && mysqli_num_rows($result) > 0) {
            // Fetching user data from the result
            $user_data = mysqli_fetch_assoc($result);
            // Retrieving hashed password from the user data
            $hashed_password = $user_data['password'];

            // Verifying the provided password against the hashed password
            if (password_verify($password, $hashed_password)) {
                // If password verification succeeds, setting user session and redirecting to index.php
                $_SESSION['user_id'] = $user_data['user_id'];
                header("Location: index.php");
                exit();
            } else {
                // If password verification fails, setting an error message
                $error_message = "Incorrect password.";
            }
        } else {
            // If no user is found with the provided username, setting an error message
            $error_message = "User not found.";
        }
    } else {
        // If any field is empty or the username contains numeric characters, setting an error message
        $error_message = "Please fill in all fields correctly.";
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KineticKards Login</title>
    <link rel="stylesheet" href="../styles/navbar-style.css">

    <link rel="stylesheet" href="../styles/login-styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
        <!-- Login form with action set to "login.php" and method set to "post" -->
        <form action="login.php" method="post">
            <h1>Login to KineticKards</h1>
            <?php if (!empty($error_message)): ?>
                <!-- Display error message in red if $error_message is not empty -->
                <p style="color: red;"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <div class="input-box">
                <!-- Input field for username with placeholder text -->
                <input type="text" name="username" placeholder="Enter your username" required>
                <!-- Icon for username input -->
                <i class='bx bxs-user'></i>
            </div>

            <div class="input-box">
                <!-- Input field for password with placeholder text -->
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
                <!-- Icon for password input and toggle button to show/hide password -->
                <i class='bx bxs-show password-toggle-btn' onclick="togglePasswordVisibility()"></i>
            </div>

            <div class="remember-forgot">
                <!-- Checkbox for "Remember me" option -->
                <label><input type="checkbox"> Remember me </label>
                <!-- Link to reset password -->
                <a href="#"> Forgot password?</a>
            </div>

            <!-- Login button -->
            <button type="submit" class="btn">Login</button>

            <div class="register-link">
                <!-- Link to register page -->
                <p>Don't have an account? <a href="../LoginPage/signup.php"> Register </a></p>
            </div>
        </form>
    </div>
</div>


<script>
    // Function to toggle password visibility
    function togglePasswordVisibility() {
        // Get the password field element
        var passwordField = document.getElementById("password");
        // Check if the password field is currently of type "password"
        if (passwordField.type === "password") {
            // If it is, change it to type "text" to make the password visible
            passwordField.type = "text";
            // Remove the class for showing the password icon and add the class for hiding it
            document.querySelector('.password-toggle-btn').classList.remove('bxs-show');
            document.querySelector('.password-toggle-btn').classList.add('bxs-hide');
        } else {
            // If the password field is not of type "password", change it back to type "password"
            passwordField.type = "password";
            // Remove the class for hiding the password icon and add the class for showing it
            document.querySelector('.password-toggle-btn').classList.remove('bxs-hide');
            document.querySelector('.password-toggle-btn').classList.add('bxs-show');
        }
    }
</script>


</body>
<footer>
    <p>&copy; 2023 KineticKards</p>
</footer>
</html>