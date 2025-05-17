<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "midnight_serpent_users";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Query database
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // After successful login
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $username;

    // Redirect to forum page
    header("Location: forum.php");
    exit;
    } else {
        // Login failed
        echo "<script>alert('Invalid credentials. The shadows reject you.'); window.location.href = 'login.html';</script>";
    }
}

$conn->close();
?>

