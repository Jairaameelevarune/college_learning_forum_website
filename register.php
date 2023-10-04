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

$username = $_POST['username'];
$password = $_POST['password'];

// Perform validation on username and password
if (empty($username) || empty($password)) {
    echo "<script>alert('Username and password are required.');</script>";
    exit();
}

if (strlen($password) < 6) {
    echo "<script>alert('Password should be at least 6 characters long.');</script>";
    exit();
}

// You should hash the password for security before storing it in the database
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";
if ($conn->query($sql) === TRUE) {
    // Set the user ID in the session after successful registration
    $_SESSION['user_id'] = $conn->insert_id;

    header("Location: forum.php");
    echo "<script>alert('Registration successful!');</script>";
    exit(); // Make sure to exit after the redirect
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
