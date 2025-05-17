<?php
//Database connection
$conn = new mysqli("localhost", "root", "", "midnight_serpent_users");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error); // Check connection

// Handle voting
if (isset($_POST['vote'])) { // Checks if a vote form was submitted
    $post_id = intval($_POST['post_id']); // Safely converts the post_id into an integer to prevent injection
    $vote_type = $_POST['vote_type']; // Retrieves the vote type (upvote or downvote)
    $vote_value = ($vote_type == 'upvote') ? 1 : -1; // Sets the vote value to +1 for upvote, -1 for downvote
    
    // Updates the post's vote count in the database
    $conn->query("UPDATE posts SET votes = votes + ($vote_value) WHERE id = $post_id");
    header("Location: forum.php");
    exit;
}

// Handle comment submission
if (isset($_POST['add_comment'])) {
    $post_id = intval($_POST['post_id']);
    // Escapes the comment content and author to prevent SQL injection.
    $content = $conn->real_escape_string($_POST['content']);
    $author = $conn->real_escape_string($_POST['author']);

    // Adds the comment into the comments table
    $conn->query("INSERT INTO comments (post_id, author, content) VALUES ($post_id, '$author', '$content')");
    header("Location: forum.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Forum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/forum.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <<a class="navbar-brand" href="#">
                <img src="Images/LOGO.png" alt="MidNight Serpent Logo" class="logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html #about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html #blog">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html #contact">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <!-- Main Posts Area -->
            <div class="col-md-8">
                <!-- Create Post Button -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Recent Posts</h4>
                    <button class="btn btn-crimson" data-bs-toggle="modal" data-bs-target="#createPostModal">
                        <i class="fas fa-plus-circle me-1"></i> Create Post
                    </button>
                </div>

                <!-- Posts List -->
                <?php
                $posts = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
                while($post = $posts->fetch_assoc()) {
                    $post_id = $post['id'];
                    $comments_count = $conn->query("SELECT COUNT(*) as count FROM comments WHERE post_id = $post_id")->fetch_assoc()['count'];
                ?>
                <div class="post-card mb-3">
                    <div class="row g-0">
                        <!-- Vote Area -->
                        <div class="col-auto vote-area">
                            <form method="post">
                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                <input type="hidden" name="vote_type" value="upvote">
                                <button type="submit" name="vote" class="vote-btn">
                                    <i class="fas fa-arrow-up"></i>
                                </button>
                            </form>
                            <div class="vote-count"><?php echo $post['votes']; ?></div>
                            <form method="post">
                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                <input type="hidden" name="vote_type" value="downvote">
                                <button type="submit" name="vote" class="vote-btn">
                                    <i class="fas fa-arrow-down"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Post Content -->
                        <div class="col">
                            <div class="card-body">
                                <h5 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h5>
                                <p class="post-meta">
                                    Posted by <span class="text-danger"><?php echo htmlspecialchars($post['author']); ?></span> 
                                    in <span class="text-danger"><?php echo htmlspecialchars($post['category']); ?></span> 
                                    • <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                                </p>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                                
                                <!-- Comment Count -->
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#comments-<?php echo $post['id']; ?>">
                                    <i class="fas fa-comment me-1"></i> <?php echo $comments_count; ?> Comments
                                </button>
                                
                                <!-- Comments Section (Collapsible) -->
                                <div class="collapse mt-3" id="comments-<?php echo $post['id']; ?>">
                                    <div class="comment-area">
                                        <h6 class="mb-3">Comments</h6>
                                        
                                        <?php
                                        $comments = $conn->query("SELECT * FROM comments WHERE post_id = $post_id ORDER BY created_at ASC");
                                        if($comments->num_rows > 0) {
                                            while($comment = $comments->fetch_assoc()) {
                                        ?>
                                        <div class="comment mb-3">
                                            <div class="comment-meta">
                                                <span class="text-danger"><?php echo htmlspecialchars($comment['author']); ?></span>
                                                • <?php echo date('M d, Y', strtotime($comment['created_at'])); ?>
                                            </div>
                                            <div class="comment-text">
                                                <?php echo htmlspecialchars($comment['content']); ?>
                                            </div>
                                        </div>
                                        <?php
                                            }
                                        } else {
                                            echo "<p class='text-muted small'>No comments yet. Be the first to comment!</p>";
                                        }
                                        ?>
                                        
                                        <!-- Add Comment Form -->
                                        <form method="post" class="mt-3">
                                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                            <div class="mb-2">
                                                <textarea class="form-control" name="content" rows="2" placeholder="Add a comment..." required></textarea>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <input type="text" class="form-control form-control-sm" name="author" placeholder="Your name" style="width: 150px;">
                                                <button type="submit" name="add_comment" class="btn btn-sm btn-crimson">Comment</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- About Forum -->
                <div class="sidebar-card">
                    <div class="sidebar-header">About The Forum</div>
                    <div class="sidebar-body">
                        <p>Welcome to the Midnight Serpent Forum - where shadows and knowledge intertwine.</p>
                        <p>Share your thoughts, ask questions, and connect with fellow seekers of hidden truths.</p>
                    </div>
                </div>
                
                <!-- Rules -->
                <div class="sidebar-card">
                    <div class="sidebar-header">Forum Rules</div>
                    <div class="sidebar-body">
                        <ol class="ps-3 mb-0">
                            <li>Respect other members</li>
                            <li>No spam or self-promotion</li>
                            <li>Use appropriate categories</li>
                            <li>What is sworn in shadow remains in the void</li>
                            <li>Every light cast demands equal darkness</li>
                        </ol>
                    </div>
                </div>
                
                <!-- Categories -->
                <div class="sidebar-card">
                    <div class="sidebar-header">Categories</div>
                    <div class="sidebar-body">
                        <ul class="list-group list-group-flush">
                            <?php
                            $categories = $conn->query("SELECT DISTINCT category FROM posts");
                            while($cat = $categories->fetch_assoc()) {
                                echo '<li class="list-group-item bg-transparent text-light border-secondary">'.htmlspecialchars($cat['category']).'</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Post Modal -->
    <div class="modal fade" id="createPostModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Create New Post</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="create_post.php" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea class="form-control" name="content" rows="5" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select bg-dark text-light border-secondary" name="category">
                                <option value="General">General</option>
                                <option value="Mysteries">Mysteries</option>
                                <option value="Symbolism">Symbolism</option>
                                <option value="Cryptography">Cryptography</option>
                                <option value="Rituals">Rituals</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Your Name</label>
                            <input type="text" class="form-control" name="author" required>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-crimson">Create Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer from index.html -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-discord"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-telegram"></i></a>
                </div>
                <div class="copyright">
                    &copy; 2025 <span>Midnight Serpent</span>. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    

    <!-- Create Post Handler -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

