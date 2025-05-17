<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "midnight_serpent";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create messages table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS messages (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sql)) {
    die("Error creating table: " . $conn->error);
}

// Load PHPMailer from your directory structure
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-6.9.3/src/Exception.php';
require 'PHPMailer-6.9.3/src/PHPMailer.php';
require 'PHPMailer-6.9.3/src/SMTP.php';

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        // PHPMailer setup
        $mail = new PHPMailer(true);
        try {
            // SMTP Configuration (Gmail example)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'oshadar.rodrigo@gmail.com';
            $mail->Password   = 'tvly fhiu ttfo nmmv';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('no-reply@midnightserpent.com', 'Midnight Serpent');
            $mail->addAddress('oshadar.rodrigo@gmail.com');
            $mail->addReplyTo($email, $name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = "New Message from MidNight Serpent Website";
            $mail->Body    = "
                <h2>New Contact Form Submission</h2>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Message:</strong></p>
                <p>$message</p>
            ";

            $mail->send();
            echo "<script>
                alert('Your message has been sent through the veil.');
                window.location.href = 'index.html';
            </script>";
        } catch (Exception $e) {
            echo "<script>
                alert('Message sent to archives, but the raven failed to deliver. Error: {$mail->ErrorInfo}');
                window.location.href = 'index.html';
            </script>";
        }
    } else {
        echo "<script>
            alert('The shadows rejected your message. Please try again.');
            window.location.href = 'index.html';
        </script>";
    }

    $stmt->close();
}

$conn->close();
?>