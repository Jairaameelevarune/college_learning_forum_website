<?php
session_start(); // Start the session (if not already started)

$servername = "localhost";
$username = "id20899253_raameele";
$password = "Jair@2002123";
$database = "id20899253_collegeforum";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$currentDate = date("Y-m-d"); // Get the current date
date_default_timezone_set('Asia/Kolkata');
$createdTime = date('H:i:s'); // Fetches the current time in the Indian timezone


$questionId = isset($_POST['question_id']) ? $_POST['question_id'] : null;
$comment = isset($_POST['comment']) ? $_POST['comment'] : null;
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Assuming user ID is stored in the session

if ($questionId && $comment && $userId) {
    // Prepare the SQL statement using placeholders
    $sql = "INSERT INTO comments (question_id, comment, votes, user_id, created_date, created_time) VALUES (?, ?, 0, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind the parameters with the corresponding values
        $stmt->bind_param("issss", $questionId, $comment, $userId, $currentDate, $currentTime);
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: forum.php"); // Redirect back to the main page after submitting a comment
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid question ID or comment.";
}

$conn->close();
?>
