<?php
session_start();
if (!isset($_SESSION['username'])) { // Check if user is logged in
    // If not logged in, redirect to login page
    header("Location: login.html");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "midnight_serpent_users";

$conn = new mysqli($servername, $username, $password, $dbname); // Create connection
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if post ID is set
$post_id = intval($_GET['id']);
$username = $_SESSION['username']; // Get the logged-in username

// Check if post exists and belongs to user
$result = $conn->query("SELECT * FROM posts WHERE id=$post_id AND author='$username'");
if ($result->num_rows == 0) {
    header("Location: forum.php");
    exit;
}

$post = $result->fetch_assoc(); // Store the post data as an associative array

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $title = $conn->real_escape_string($_POST['title']); // Escape special characters in title
    $content = $conn->real_escape_string($_POST['content']); // Escape special characters in content
    $category = $conn->real_escape_string($_POST['category']);  // Escape special characters in content
    
    $sql = "UPDATE posts SET title='$title', content='$content', category='$category' WHERE id=$post_id AND author='$username'"; // Update the post in the database
    
    // Check if update was successful
    if ($conn->query($sql) === TRUE) {
        header("Location: forum.php"); // Redirect to forum page
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post - Midnight Serpent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body { background-color: #0a0a0a; color: #e0e0e0; }
        .form-control, .form-select { background-color: #1a1a1a; color: #e0e0e0; border-color: #8b0000; }
        .form-control:focus, .form-select:focus { background-color: #1a1a1a; color: #e0e0e0; border-color: #dc143c; box-shadow: 0 0 0 0.25rem rgba(139, 0, 0, 0.25); }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card bg-dark text-light">
                    <div class="card-header bg-danger text-white">
                        <h3>Edit Post</h3>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea class="form-control" id="content" name="content" rows="6" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="General" <?php if($post['category'] == 'General') echo 'selected'; ?>>General</option>
                                    <option value="Mysteries" <?php if($post['category'] == 'Mysteries') echo 'selected'; ?>>Mysteries</option>
                                    <option value="Symbolism" <?php if($post['category'] == 'Symbolism') echo 'selected'; ?>>Symbolism</option>
                                    <option value="Cryptography" <?php if($post['category'] == 'Cryptography') echo 'selected'; ?>>Cryptography</option>
                                    <option value="Rituals" <?php if($post['category'] == 'Rituals') echo 'selected'; ?>>Rituals</option>
                                </select>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="forum.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-danger">Update Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
