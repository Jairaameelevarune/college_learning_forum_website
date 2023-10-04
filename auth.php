<?php
session_start(); // Start the session

// Check if the user is logged in
if(isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} else {
    header("Location:registration.html");
    // Redirect to the login page or handle the case when the user is not logged in
    // ...
    exit; // It's important to exit or stop the execution if the user is not logged in
}
?>
