<?php
session_start();
include 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") { // checks the form is submitted using POST method
    $user_id = $_SESSION['user_id'];
    $quiz_id = $_POST['quiz_id'];
    $user_answer = $_POST['answer']; 

    $con = connection();

    // retrieves the correct answer for the given quiz ID
    $query = "SELECT correctanswer FROM flashcards_quiz.quiz WHERE quiz_id = ?";
    $statement = $con->prepare($query);
    $statement->bind_param("i", $quiz_id);
    $statement->execute();
    $result = $statement->get_result();

    if ($result->num_rows > 0) { // checks the quiz exists and retrieve the correct answer
        $row = $result->fetch_assoc();
        $correct_answer = $row['correctanswer'];
    } else {
        exit("Quiz not found.");
    }

    // check if the answer is correct
    $is_correct = ($user_answer == $correct_answer);
    
    // updates the user's tally based on the correctness of the answer
    if ($is_correct) {
        $update_query = "UPDATE dbusers SET correct = correct + 1 WHERE user_id = ?";
    } else {
        $update_query = "UPDATE dbusers SET incorrect = incorrect + 1 WHERE user_id = ?";
    }
    
    $update_statement = $con->prepare($update_query);
    $update_statement->bind_param("i", $user_id);
    $update_statement->execute();


    
    // adjust correct count based on correctness
    $correct_increment = ($is_correct) ? 1 : -1; // Increment if correct, decrement if incorrect

    // get the current correct count, next appearance, and last answered time
    $query = "SELECT correct_count, next_appearance, last_answered FROM users.user_question_performance WHERE user_id = ? AND quiz_id = ?";
    $statement = $con->prepare($query);
    $statement->bind_param("ii", $user_id, $quiz_id);
    $statement->execute();
    $result = $statement->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_correct_count = $row['correct_count'];
        $next_appearance = $row['next_appearance'];
        $last_answered = $row['last_answered'];
    } else {
        $current_correct_count = 0;
        $next_appearance = time(); 
        $last_answered = null; 
    }

    // calculate new correct count within the range of 0 to 5
    $new_correct_count = min(max($current_correct_count + $correct_increment, 0), 5);

    // calcilate next appearance timestamp based on correct count
    $next_appearance_offset = (60 * $new_correct_count); //  frequency based on correct count, exponential backoff
    $next_appearance = time() + $next_appearance_offset; // calculate next appearance timestamp

    // next appearance timestamp to MySQL DATETIME format
    $next_appearance_datetime = date('Y-m-d H:i:s', $next_appearance);

    // last answered time to current time
    $last_answered_datetime = date('Y-m-d H:i:s'); 


    $query = "INSERT INTO users.user_question_performance (user_id, quiz_id, correct_count, next_appearance, last_answered) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE correct_count = ?, next_appearance = ?, last_answered = ?";
    $statement = $con->prepare($query);
    $statement->bind_param("iiisssss", $user_id, $quiz_id, $new_correct_count, $next_appearance_datetime, $last_answered_datetime, $new_correct_count, $next_appearance_datetime, $last_answered_datetime);
    $statement->execute();

    mysqli_close($con);

    $_SESSION['answer_correct'] = $is_correct;

    header("Location: quiz.php");
    exit();
}
?>
