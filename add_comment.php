<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html"); // Redirect to login page if not logged in
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "midnight_serpent_users";

// Create connection with MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if connection was failed
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted (Ensures that the code runs only when a form is submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = intval($_POST['post_id']); // Ensure post_id is an integer
    $content = $conn->real_escape_string($_POST['content']); // Escape special characters in the content to prevent SQL injection
    $author = $_SESSION['username']; // Get the username from the session
    
    // Creates an SQL query to insert the new comment into the comments table with the post ID, author, and comment content
    $sql = "INSERT INTO comments (post_id, author, content) 
            VALUES ('$post_id', '$author', '$content')";
    
    // Execute the query and check if it was successful
    // If the query was successful, redirect to the forum page
    if ($conn->query($sql) === TRUE) {
        header("Location: forum.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

