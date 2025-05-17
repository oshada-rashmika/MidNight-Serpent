<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "midnight_serpent_users";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Connection failed']));
}

// Get data from POST request
$data = json_decode(file_get_contents('php://input'), true);
$post_id = intval($data['post_id']);
$upvote = $data['upvote'] ? 1 : -1;

// Update votes
$sql = "UPDATE posts SET votes = votes + ($upvote) WHERE id = $post_id";
if ($conn->query($sql) === TRUE) {
    // Get updated vote count
    $result = $conn->query("SELECT votes FROM posts WHERE id = $post_id");
    $row = $result->fetch_assoc();
    echo json_encode(['success' => true, 'newVotes' => $row['votes']]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

$conn->close();
?>
