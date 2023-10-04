<?php
session_start();
$servername = "localhost";
$username = "id20899253_raameele";
$password = "Jair@2002123";
$database = "id20899253_collegeforum";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$currentDate = date("Y-m-d");
date_default_timezone_set('Asia/Kolkata');
$createdTime = date('H:i:s'); // Fetches the current time in the Indian timezone
// Get the current date


$question = $_POST['question'];
$userId = $_SESSION['user_id'];

$sql = "INSERT INTO questions (question, votes, user_id, created_date, created_time) VALUES ('$question', 0, $userId, '$currentDate', '$currentTime')";

if ($conn->query($sql) === TRUE) {
    header("Location: forum.php"); // Redirect back to the main page after submitting a question
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
