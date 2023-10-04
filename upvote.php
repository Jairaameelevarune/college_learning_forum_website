<?php
require_once 'auth.php';

$servername = "localhost";
$username = "id20899253_raameele";
$password = "Jair@2002123";
$database = "id20899253_collegeforum";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$questionId = $_GET['id']; // Assuming the question ID is passed as a GET parameter

// Update the votes for the question in the database
$sql = "UPDATE questions SET votes = votes + 1 WHERE id = $questionId";
if ($conn->query($sql) !== true) {
    echo "Error updating question votes: " . $conn->error;
}

// Get the question ID from the URL parameter
$questionId = $_GET['id'];

// Check if the user has already upvoted the question
$voteType = 'question'; // Set the vote type to 'question'
$sqlCheckVote = "SELECT * FROM votes WHERE user_id = '$userId' AND question_id = '$questionId' AND vote_type = '$voteType'";
$resultCheckVote = $conn->query($sqlCheckVote);

if ($resultCheckVote->num_rows > 0) {
    // User has already upvoted this question
    echo "You have already upvoted this question.";
    header("Location: index.php"); // Redirect back to index.php
} else {
    // Insert the vote into the database
    $sqlInsertVote = "INSERT IGNORE INTO votes (user_id, question_id, vote_type) VALUES ('$userId', '$questionId', '$voteType')";
    $resultInsertVote = $conn->query($sqlInsertVote);

    // Handle the result of the insert operation (e.g., display success or error message)
    if ($resultInsertVote === TRUE) {
        // Check if the vote was inserted or ignored
        if ($conn->affected_rows > 0) {
            // Vote inserted successfully
            echo "Question upvoted successfully.";
        } else {
            // Vote already exists (ignored)
            echo "You have already upvoted this question.";
        }
    } else {
        echo "Error upvoting question: " . $conn->error;
    }
}

$conn->close();
?>
