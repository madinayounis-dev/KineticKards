<?php 
include("functions.php");
$con = connection();

$user_data = check_login($con); // Function created to collect the users data (does not exist yet)

// Check if user is logged in
if($user_data === null) {
    // Redirect to login page or handle unauthorised access
    header("Location: login.php");
    exit;
}
$username = ucfirst($user_data['user_name']); // Variable which stores the users username from the user_data array and capitalises it.
$correct_tally = ($user_data['correct']); // Variable which stores the users correct answers from the user_data array 
$incorrect_tally = ($user_data['incorrect']); //  Variable which stores the users incorrect answers from the user_data array 
$total_questions = $correct_tally + $incorrect_tally; // Variable which stores the total amount of questions

// Check if total_questions is not zero to avoid division by zero error
if ($total_questions != 0) {
    // Calculate percentage
    $percentage = ($correct_tally / $total_questions) * 100;

    // Calculate grade based on percentage
    if ($percentage >= 80) {
        $grade = 'A+';
    } elseif ($percentage >= 70) {
        $grade = 'A';
    } elseif ($percentage >= 60) {
        $grade = 'B';
    } elseif ($percentage >= 40) {
        $grade = 'C'; 
    } else {
        $grade = 'F';
    }
} else {
    // If total_questions is zero, set grade to 'N/A' or handle it as needed
    $grade = 'N/A';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $username ?>'s Dashboard</title> <!-- Update title to include user's username -->

    <link rel="stylesheet" href="../styles/index-styles.css"> <!-- Import index-style.css -->
    <link rel="stylesheet" href="../styles/navbar-style.css"> <!-- Import navbar-style.css -->
    

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/css/boxicons.min.css">    
</head>
<body>
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

    <!-- Header -->
    <div class="header">
        <h1><?php echo $username ?>'s Dashboard</h1> <!-- Uses php to display the username -->  
        <p>The Most Interactive Physics Flashcards Website</p>
    </div>
    <div class="container">
    <div class="card dashboard">
            <h2><?php echo $username ?>'s Dashboard</h2> <!-- Uses php to display the username -->  
            <ul>
                <!-- Navigation links -->
                <li><a href="topics.html" class="button">Statistics</a></li> <!-- Link to Statistics page -->
                <li><a href="create-flashcards.html" class="button">Create Flashcards</a></li> <!-- Link to Create Flashcards page -->
                <li><a href="leaderboard.html" class="button">Leaderboard</a></li> <!-- Link to Leaderboard page -->
            </ul>

        </div>
        <div class="card dashboard">
    <!-- Dashboard section for Topics -->
    <h2>Topics</h2>
    <ul>
        <!-- List items for individual topics -->
        <li><a href="topic1.php" class="button">1</a></li>
        <li><a href="topic2.php" class="button">2</a></li>
        <li><a href="topic3.php" class="button">3</a></li>
        <li><a href="topic4.php" class="button">4</a></li>
        <li><a href="topic5.php" class="button">5</a></li>
        <li><a href="topic6.php" class="button">6</a></li>
        <li><a href="topic7.php" class="button">7</a></li>
        <li><a href="topic8.php" class="button">8</a></li>
    </ul>
    </div>
    <div class="card dashboard">
        <!-- Dashboard section for Topic Quizzes -->
        <h2>Topic Quizes</h2>
        <ul>
            <!-- List items for individual topic quizzes -->
            <li><a href="quiz.php" class="button">1</a></li>
            <li><a href="topic2.php" class="button">2</a></li>
            <li><a href="topic3.php" class="button">3</a></li>
            <li><a href="topic4.php" class="button">4</a></li>
            <li><a href="topic5.php" class="button">5</a></li>
            <li><a href="topic6.php" class="button">6</a></li>
            <li><a href="topic7.php" class="button">7</a></li>
            <li><a href="topic8.php" class="button">8</a></li>
        </ul>
    </div>

        <div class="card">
            <h2>Your Performance</h2>
            <div class="performance">
                <div class="performance-item">
                    <div class="circle correct">
                        <!-- Display the count of correctly answered questions here. Uses php to display the variable -->
                        <span><?php echo $correct_tally ?></span> 
                    </div>
                    <p>Correct Answers</p>
                </div>
                <div class="performance-item">
                    <div class="circle incorrect">
                        <!-- Display the count of incorrectly answered questions here. Uses php to display the variable -->
                        <span><?php echo $incorrect_tally ?></span>
                    </div>
                    <p>Incorrect Answers</p>
                </div>
                <div class="performance-item">
                    <div class="circle grade">
                        <!-- Display performance metric here. Uses php to display the variable -->
                        <span><?php echo $grade ?></span>
                    </div>
                    <p>Grade</p>
                </div>
            </div>
        </div>

        <div class="card">
        <!-- This is a card element -->
        <h2>Upcoming Tests</h2>
        <!-- Heading indicating upcoming tests -->
        <div class="upcoming-tests">
            <!-- Container for upcoming tests -->
            <div class="upcoming-test">
                <!-- Container for a single upcoming test -->
                <h3>Physics Paper 1</h3>
                <!-- Title of the test -->
                <p>Date: March 25th 2024</p>
                <!-- Date of the test -->
            </div>
        </div>
        <button class="add-test-button"><i class='bx bx-plus'></i></button>
        <!-- Button for adding a new test -->
    </div>



    </div>
    <footer>
    <p>&copy; 2024 KineticKards</p>
</footer>


        <script>

        // Wait for the DOM content to be fully loaded
        document.addEventListener("DOMContentLoaded", function() {
            // Add a click event listener to the entire document
            document.addEventListener("click", function(event) {
                // Check if the clicked element has the class "add-test-button"
                if (event.target.classList.contains("add-test-button")) {
                    // Prompt the user to enter the test name
                    const testName = prompt("Enter the test name:");
                    // Prompt the user to enter the test date
                    const testDate = prompt("Enter the test date (e.g., March 25, 2024):");

                    // Check if both the test name and test date are not empty
                    if (testName !== null && testName.trim() !== "" && testDate !== null && testDate.trim() !== "") {
                        // Get the container for upcoming tests
                        const upcomingTestsContainer = document.querySelector(".upcoming-tests");
                        // Create a new div element to represent the upcoming test
                        const upcomingTest = document.createElement("div");
                        // Add a class to the upcoming test div
                        upcomingTest.classList.add("upcoming-test");
                        // Set the HTML content of the upcoming test div
                        upcomingTest.innerHTML = `
                            <h3>${testName}</h3>
                            <p>Date: ${testDate}</p>
                        `;
                        // Append the upcoming test div to the container for upcoming tests
                        upcomingTestsContainer.appendChild(upcomingTest);
                    } else {
                        // If either test name or test date is empty, show an alert
                        alert("Test name and date cannot be empty.");
                    }
                }
            });
        });

    </script>
</body>

</html>
