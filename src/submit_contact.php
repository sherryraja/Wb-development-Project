<?php
// Start session if needed
session_start();

// Include the database connection
require('dbconnection.php');

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get and sanitize form data
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));  // New field
    $subject = mysqli_real_escape_string($conn, trim($_POST['subject']));  // New field
    $message = mysqli_real_escape_string($conn, trim($_POST['message']));

    // Validate the form data
    if (!empty($name) && !empty($email) && !empty($message)) {
        
        // Insert the contact message into the database, including phone and subject
        $query = "INSERT INTO contacts (name, email, phone, subject, message) 
                  VALUES ('$name', '$email', '$phone', '$subject', '$message')";
        
        if (mysqli_query($conn, $query)) {
            // Redirect with success message or return JSON response
            echo "Message sent successfully!";
            header("Location: contact_success.php"); // Optional: redirect to a success page
        } else {
            // Handle the error
            echo "Error: Could not send message. " . mysqli_error($conn);
        }

    } else {
        // Handle the case where form data is incomplete
        echo "All fields except phone and subject are required.";
    }
} else {
    echo "Invalid request.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Success</title>
    <link rel="stylesheet" type="text/css" href="submit_contact.css">
</head>
<body>
    <div class="success-message">
        <h1>Message sent successfully!</h1>
        <p><a href="contact_success.php">Click here to see more details.</a></p>
    </div>
</body>
</html>

   