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

// Check if invitation code exists
function checkInvitationCode($conn, $invitationCode) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE InvitationCode = ?");
    $stmt->bind_param("s", $invitationCode);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Register user
function registerUser($conn, $username, $password, $invitationCode) {
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return "Username already exists.";
    }

    // Insert user into database
    $currentDate = date("Y-m-d H:i:s"); // Get current date and time
    $stmt = $conn->prepare("INSERT INTO users (Username, Password, InvitationCode, RegistrationDate) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $hashedPassword, $invitationCode, $currentDate);
    if ($stmt->execute()) {
        return true;
    } else {
        return "Error: " . $conn->error;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $invitationCode = $_POST['invitationCode'];

    if (checkInvitationCode($conn, $invitationCode)) {
        $result = registerUser($conn, $username, $password, $invitationCode);
        if ($result === true) {
            // Registration successful
            echo "<script>alert('Registration successful! Welcome to the shadows.'); window.location.href = 'forum.php';</script>";
        } else {
            // Registration failed
            echo "<script>alert('Registration failed: " . $result . "'); window.location.href = 'register.html';</script>";
        }
    } else {
        // Invalid invitation code
        echo "<script>alert('Invalid invitation code. The shadows reject you.'); window.location.href = 'register.html';</script>";
    }
}

// Close connection
$conn->close();
?>




