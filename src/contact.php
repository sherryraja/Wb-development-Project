<?php
require('dbconnection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <!-- Link to your CSS file -->
    <link rel="stylesheet" href="contact.css">
</head>
<body>
    <div class="container">
        <form action="contact.php" method="POST">
            <h2>Contact Us</h2>

            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <input type="text" name="phone" placeholder="Your Phone">
            <input type="text" name="subject" placeholder="Subject">
            <textarea name="message" placeholder="Your Message" required></textarea>

            <input type="submit" value="Send Message">
        </form>

        <?php
        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve and sanitize form data
            $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
            $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
            $phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : '';
            $subject = isset($_POST['subject']) ? htmlspecialchars(trim($_POST['subject'])) : '';
            $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';

            // Ensure required fields are not empty
            if (!empty($name) && !empty($email) && !empty($message)) {
                // Prepare and execute SQL query
                $sql = "INSERT INTO contacts (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                if ($stmt === false) {
                    die("Error preparing statement: " . $conn->error);
                }

                $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);

                if ($stmt->execute()) {
                    echo '<div class="success">Message sent successfully!</div>';
                } else {
                    echo '<div class="error">Error: ' . $stmt->error . '</div>';
                }

                $stmt->close();
            } else {
                echo '<div class="error">Name, email, and message fields are required.</div>';
            }
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
