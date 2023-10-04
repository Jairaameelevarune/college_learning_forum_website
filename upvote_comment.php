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

$commentId = $_GET['id']; // Assuming the comment ID is passed as a GET parameter

// Check if the user has already upvoted the comment
$voteType = 'comment'; // Set the vote type to 'comment'
$sqlCheckVote = "SELECT * FROM votes WHERE user_id = '$userId' AND comment_id = '$commentId' AND vote_type = '$voteType'";
$resultCheckVote = $conn->query($sqlCheckVote);

if ($resultCheckVote->num_rows > 0) {
    // User has already upvoted this comment
    echo "You have already upvoted this comment.";
} else {
    // Update the votes for the comment in the database
    $sql = "UPDATE comments SET votes = votes + 1 WHERE id = $commentId";
    if ($conn->query($sql) !== true) {
        echo "Error updating comment votes: " . $conn->error;
    }

    // Insert the vote into the database
    $sqlInsertVote = "INSERT INTO votes (user_id, comment_id, vote_type) VALUES ('$userId', '$commentId', '$voteType')";
    $resultInsertVote = $conn->query($sqlInsertVote);

    // Handle the result of the insert operation (e.g., display success or error message)
    if ($resultInsertVote === TRUE) {
        // Vote inserted successfully
        echo "Comment upvoted successfully.";
    } else {
        echo "Error upvoting comment: " . $conn->error;
    }
}

$conn->close();
?>
