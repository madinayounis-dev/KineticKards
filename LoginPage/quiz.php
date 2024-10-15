<?php
include 'functions.php';

// check if user is logged in
$user_data = check_login(connection());

if (!$user_data) { // redirect the user to the login page if not logged in
    header("Location: login.php");
    exit();
}

// get the user ID from the user data
$user_id = $user_data['user_id'];
$con = connection();
// execute query to fetch the correct and incorrect counts from the database for the user
$query = "SELECT correct, incorrect FROM dbusers WHERE user_id = ?";
$statement = $con->prepare($query);
$statement->bind_param("i", $user_id);
$statement->execute();
$result = $statement->get_result();

$correct_count = 0;
$incorrect_count = 0;

// if query result contains rows, fetch the correct and incorrect counts
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $correct_count = $row['correct'];
    $incorrect_count = $row['incorrect'];
}
mysqli_close($con); // close db connection

// get a random question
$con = connection();
$question_data = get_random_question($con, $user_id);

// fetch correct count for this specific question
$correct_count_for_question = 0;
if ($question_data) {
    $quiz_id = $question_data['quiz_id'];
    $query = "SELECT correct_count FROM user_question_performance WHERE user_id = ? AND quiz_id = ?";
    $statement = $con->prepare($query);
    $statement->bind_param("ii", $user_id, $quiz_id);
    $statement->execute();
    $result = $statement->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $correct_count_for_question = $row['correct_count'];
    }
}
mysqli_close($con); // close db connection
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>KineticKards Login</title>
        <link rel="stylesheet" href="../styles/navbar-style.css">
        <link rel="stylesheet" href="../styles/flashcards-styles.css">

        <link rel="stylesheet" href="../styles/login-styles.css">
        <link rel="stylesheet" href="../styles/quiz-style.css">

        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <title>Infinite Quiz</title>
        <style>
        </style>
    </head>
    <style>
        
    </style>
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

    <div class="container">
    <!-- Container for the entire page -->
    <div class="header">
        <!-- Header section -->
        <h1>Physics Flashcards</h1>
    </div>
    <div class="card dashboard">
        <!-- Dashboard card section -->
        <div class="flashcard">
            <!-- Flashcard container -->
            <div class="flashcard-header">
                <!-- Header of the flashcard -->
                <button class="flashcard-header-button"><i class='bx bx-bulb'></i> Hint</button>
                <!-- Button for displaying hint -->
                <div class="flashcard-header-icons">
                    <!-- Container for header icons -->
                    <button class="flashcard-header-button"><i class='bx bx-heart'></i></button>
                    <!-- Button for adding to favorites -->
                    <button class="flashcard-header-button"><i class='bx bx-volume-full'></i></button>
                    <!-- Button for audio -->
                </div>
            </div>
            <div class="flashcard-content" onclick="toggleCard(this)">
                <!-- Content section of the flashcard with onclick event -->
                <div class="flashcard-front">
                    <!-- Front side of the flashcard -->
                    <?php
                        // Get a random question
                        $con = connection();
                        $question_data = get_random_question($con, $user_id);

                        if (!$question_data) {
                            echo "No questions available.";
                        } else {
                            display_quiz_question($question_data);
                        }

                        mysqli_close($con);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>



        </div>
        <div class="flashcard-navigation">
    <!-- Navigation section for the flashcards -->
    <div class="button face" onclick=""><i class='bx bx-sad' style="color:coral;"></i></div>
    <!-- Button for indicating incorrect answer -->
    <div class="incorrect-tally" style="color:red;"><?php echo $incorrect_count; ?></div>
    <!-- Displaying the count of incorrect attempts -->
    <div class="flashcard-indicator" style="margin:0px 20px; color: ;" >
        <?php 
            echo "$correct_count_for_question / 5"; // Displaying the current correct count for the question
        ?>
    </div>
    <div class="flashcard-indicator correct" style="color:lime;"><?php echo $correct_count; ?></div>
    <!-- Displaying the overall correct count -->
    <div class="button face" onclick=""><i class='bx bx-smile' style="color:lime;"></i></div>
    <!-- Button for indicating correct answer -->
</div>

    </div>
    <div id="end-screen" class="container" style="display: none;">
    <!-- This div represents the end screen of the quiz -->
    <div class="card">
        <h2>Quiz Ended</h2>
        <p>Your score is: <span id="score"></span></p>
        <p>Incorrect Answers: <span id="incorrect-count"></span></p>
    </div>
</div>


<script>
    // This event listener waits for the DOM content to be fully loaded before executing the code
    document.addEventListener("DOMContentLoaded", function() {
        
        // Select all radio button options
        const options = document.querySelectorAll('input[type="radio"]');

        // Loop through each radio button option
        options.forEach(option => {
            // Add an event listener for when the option is clicked
            option.addEventListener("click", function() {
                
                // Get the value of the selected answer
                const selectedAnswer = this.value;
                
                // Select all radio button options again
                const allOptions = document.querySelectorAll('input[type="radio"]');
                
                // Loop through all options
                allOptions.forEach(opt => {
                    // Remove classes indicating correct or incorrect answers
                    opt.parentNode.classList.remove('correct-answer', 'incorrect-answer');
                    
                    // If the option value matches the selected answer, add class for correct answer, else add class for incorrect answer
                    if (opt.value === selectedAnswer) {
                        opt.parentNode.classList.add('correct-answer');
                    } else {
                        opt.parentNode.classList.add('answer-disabled-hover');
                    }
                });
            });
        });
    });

    // This part of the code seems to be a repetition of the previous block with additional PHP integration for getting the correct answer
    document.addEventListener("DOMContentLoaded", function() {
        const options = document.querySelectorAll('input[type="radio"]');
        
        options.forEach(option => {
            option.addEventListener("click", function() {
                const selectedAnswer = this.value;
                const correctAnswer = "<?php echo $question_data['correctanswer']; ?>"; // get correct answer from PHP
                const allOptions = document.querySelectorAll('input[type="radio"]');
                
                allOptions.forEach(opt => {
                    opt.parentNode.classList.remove('correct-answer', 'incorrect-answer'); // reset classes for all options
                    if (opt.value === correctAnswer) { // show the correct and incorrect options based on user selection and correct answer
                        opt.parentNode.classList.add('correct-answer');
                        opt.parentNode.classList.add('answer-disabled-hover');
                    }
                    if (opt.value === selectedAnswer && selectedAnswer !== correctAnswer) {
                        opt.parentNode.classList.add('incorrect-answer');
                        opt.parentNode.classList.add('answer-disabled-hover');
                    }
                });

                // Disable all other options
                allOptions.forEach(opt => {
                    if (opt.value !== selectedAnswer) {
                        opt.disabled = true;
                    }
                });
            });
        });
    });

    // This part seems to handle enabling/disabling of the submit button based on whether an option is selected
    document.addEventListener("DOMContentLoaded", function() {
        const options = document.querySelectorAll('input[type="radio"]');
        const submitButton = document.getElementById('submit-answer');
        
        options.forEach(option => {
            option.addEventListener("click", function() {
                const selectedAnswer = this.value;
                if (selectedAnswer) {
                    submitButton.disabled = false;
                } else {
                    submitButton.disabled = true;
                }
            });
        });
    });
</script>

    </body>
    </html>

    <?php
    // Function to get a random question for a user
    function get_random_question($con, $user_id) {
        // Check if there are unanswered questions for the user
        $unanswered_query = "SELECT q.quiz_id
                            FROM flashcards_quiz.quiz q
                            LEFT JOIN users.user_question_performance uqp 
                            ON q.quiz_id = uqp.quiz_id AND uqp.user_id = $user_id
                            WHERE uqp.user_id IS NULL";

        $unanswered_result = mysqli_query($con, $unanswered_query);

        // If there are unanswered questions, fill user_question_performance table with default values
        if ($unanswered_result && mysqli_num_rows($unanswered_result) > 0) {
            // Fill user_question_performance table with default values for unanswered questions
            while ($row = mysqli_fetch_assoc($unanswered_result)) {
                $quiz_id = $row['quiz_id'];
                $default_performance_query = "INSERT INTO users.user_question_performance (user_id, quiz_id, correct_count) 
                                            VALUES ($user_id, $quiz_id, 0)";
                mysqli_query($con, $default_performance_query);
            }
        }

        // Now fetch a random question for the user, ordered by next_appearance in ascending order
        $query = "SELECT q.*, uqp.user_id 
                FROM flashcards_quiz.quiz q 
                LEFT JOIN users.user_question_performance uqp 
                ON q.quiz_id = uqp.quiz_id AND uqp.user_id = $user_id
                ORDER BY uqp.next_appearance ASC, RAND() LIMIT 1";
        $result = mysqli_query($con, $query);

        // Return the fetched question
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }

    // Function to display a quiz question
    function display_quiz_question($question_data) {
        // Display the question and options
        echo "<h2>{$question_data['question']}</h2>";
        echo "<form method='post' action='update_tally.php'>";
        echo "<input type='hidden' name='user_id' value='{$_SESSION['user_id']}'>";
        echo "<input type='hidden' name='quiz_id' value='{$question_data['quiz_id']}'>";
        echo "<ul>";

        // Shuffle the options to avoid bias
        $options = array(
            $question_data['correctanswer'],
            $question_data['incorrect1'],
            $question_data['incorrect2'],
            $question_data['incorrect3']
        );
        shuffle($options);

        // Display options as radio buttons
        foreach ($options as $option) {
            echo "<li><label class='option'><input type='radio' name='answers' value='$option'>$option</label></li>";
        }

        echo "</ul>";
        echo "<input id='submit-answer' class='submit_answer' type='submit' value='Next' disabled>";
        echo "</form>";
    }
?>

