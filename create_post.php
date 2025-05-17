<?php
$conn = new mysqli("localhost", "root", "", "midnight_serpent_users"); // Database connection
// Check if the connection was successful or failed
// If the connection was failed, it will die and show the error message
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form is submitted
    $title = $conn->real_escape_string($_POST['title']); // Escape special characters in the title to prevent SQL injection
    $content = $conn->real_escape_string($_POST['content']); // Escape special characters in the content to prevent SQL injection
    $category = $conn->real_escape_string($_POST['category']); // Escape special characters in the category to prevent SQL injection
    $author = $conn->real_escape_string($_POST['author']); // Escape special characters in the author to prevent SQL injection
    
    // Creates an SQL query to insert the new post into the posts table with the title, content, category, author, and initial votes set to 0
    $sql = "INSERT INTO posts (title, content, category, author, votes) VALUES ('$title', '$content', '$category', '$author', 0)";
    
    // Execute the query and check if it was successful
    // If the query was successful, redirect to the forum page
    if ($conn->query($sql)) {
        header("Location: forum.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>


