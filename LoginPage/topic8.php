<?php
include 'functions.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$con = connection();
$user_id = $_SESSION['user_id'];

// Function to check if the user exists in user_performance table
function user_exists_in_performance($con, $user_id) {
    $query = "SELECT COUNT(*) as count FROM user_performance WHERE user_id = $user_id";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['count'] > 0;
}

// Function to insert user with all flashcard_ids from topic8_flashcards table
function insert_user_performance($con, $user_id) {
    $query = "INSERT INTO user_performance (user_id, flashcard_id, next_appearance) SELECT $user_id, flashcard_id, NOW() FROM flashcards.topic8_flashcards";
    mysqli_query($con, $query);
}

// Check if user exists in user_performance table
if (!user_exists_in_performance($con, $user_id)) {
    // If user doesn't exist, insert records for the user with all flashcard_ids
    insert_user_performance($con, $user_id);
}

// Function to get the flashcard based on next_appearance timestamp
function get_next_flashcard($con, $user_id) {
    $query = "SELECT flashcard_id FROM user_performance WHERE user_id = $user_id ORDER BY next_appearance ASC LIMIT 1";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['flashcard_id'];
    } else {
        return null;
    }
}

// Get the flashcard ID based on next_appearance timestamp
$flashcard_id = get_next_flashcard($con, $user_id);

// Function to get the question and answer based on flashcard ID
function get_flashcard_question_answer($con, $flashcard_id) {
    if ($flashcard_id !== null) {
        $query = "SELECT question, answer FROM flashcards.topic8_flashcards WHERE flashcard_id = $flashcard_id";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            return null;
        }
    } else {
        return null;
    }
}

// Get the question and answer for the retrieved flashcard ID
$flashcard_data = get_flashcard_question_answer($con, $flashcard_id);
$query = "SELECT mastery FROM user_performance WHERE user_id = $user_id AND flashcard_id = $flashcard_id";
$result = mysqli_query($con, $query);
$mastery_level = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result)['mastery'] : 0;

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KineticKards Dashboard</title>
    <link rel="stylesheet" href="../styles/flashcards-styles.css">
    <link rel="stylesheet" href="../styles/navbar-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.0/css/boxicons.min.css"> <!-- Add any other CSS files if needed -->
    <style>
        /* Additional or modified CSS styles can be included here */
    </style>
</head>
<body>
<nav class="navbar">
    <div class="navbar__container">
        <div class="navbar__logo">
        <img src="" alt="KineticKards" style="margin-right:60px; color:#856ef6">
            <a href="#" class="navbar__link">Your Library</a>
            <a href="#" class="navbar__link">Study Tools</a>
        </div>
        <div class="navbar__search">
            <input type="text" placeholder="Search...">
            <span class="search-icon">
                <i class='bx bx-search'></i>
            </span>
        </div>
        <div class="navbar__actions">
                <a href="#" class="navbar__action" style="display: flex; align-items: center; justify-content: center; min-width: 34px; width: 34px; height: 34px; border: 2px solid #9f91e5; border-radius: 50%; margin-right: 15px; padding: 2px; text-decoration: none;">
            <i class='bx bx-plus' style="font-size: 30px; color: #9f91e5; transform:translatex(-5px);"></i>

        </a>
        <a href="#" class="navbar__action" style="display: flex; align-items: center; justify-content: center; min-width: 34px; width: 34px; height: 34px; border: 2px solid #9f91e5; border-radius: 50%; margin-right: 15px; padding: 2px; text-decoration: none;">
            <i class='bx bx-bell' style="font-size: 28px; color: #9f91e5; transform:translatex(-4px);"></i>
        </a>

           
            <a href="#" class="navbar__action"><img src="../styles/pfp.png" alt="Profile" style="height: 46px; width: 46px; border-radius: 50%; transform:translatey(2px)"></a>

            <a href="#" class="navbar__action upgrade">Upgrade Today</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="header">
        <h1>Physics Flashcards</h1>
    </div>
    <div class="card dashboard">
        <div class="flashcard">
            <div class="flashcard-header">
                <button class="flashcard-header-button"><i class='bx bx-bulb'></i> Hint</button>
                <div class="flashcard-header-icons">
                    <button class="flashcard-header-button"><i class='bx bx-heart'></i></button>
                    <button class="flashcard-header-button"><i class='bx bx-volume-full'></i></button>
                </div>
            </div>
            <div class="flashcard-content" onclick="toggleCard(this)">
            <div class="flashcard-front">
    <p><?php echo isset($flashcard_data['question']) ? $flashcard_data['question'] : ''; ?></p>
</div>
                <div class="flashcard-back">
    <p><?php echo isset($flashcard_data['answer']) ? $flashcard_data['answer'] : ''; ?></p>
</div>
            </div>
        </div>
    </div>
    <div class="flashcard-navigation">
    <div class="button face" onclick="recordPerformance('dislike')"><i class='bx bx-sad' style="color:coral;"></i></div>

    <!-- Pass 'like' as argument to indicate a positive response -->
    <div class="flashcard-indicator">MASTERY: <?php echo $mastery_level; ?> / 5</div>
    <div class="button face" onclick="recordPerformance('like')"><i class='bx bx-smile' style="color:lime;"></i></div>

    <!-- Pass 'dislike' as argument to indicate a negative response -->

</div>

</div>

<footer>
    <p>&copy; 2023 KineticaKards</p>
</footer>

<script>

    function toggleCard(card) {
        card.classList.toggle('flipped');
    }
    function recordPerformance(action) {
    var flashcardId = "<?php echo $flashcard_id; ?>";
    // Send an AJAX request to update mastery based on action and flashcardId
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Optionally, you can handle the response here if needed
            console.log(this.responseText);
            // After updating mastery, proceed to the next flashcard
            nextFlashcard();
        }
    };
    xmlhttp.open("GET", "update_mastery.php?action=" + action + "&flashcard_id=" + flashcardId, true);
    xmlhttp.send();
}

function nextFlashcard() {
    // Reload the page to display the next flashcard
    location.reload();
}


    function toggleCard(card) {
        card.classList.toggle('flipped');
    }


    document.addEventListener("DOMContentLoaded", displayFlashcard);

</script>
</body>
</html>
