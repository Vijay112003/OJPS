<?php
session_start(); // Start the session
require_once '../utils/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $suffix = $_POST['suffix'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Handle resume upload
    $resume_dir = "../resume/";
    if (!is_dir($resume_dir)) {
        mkdir($resume_dir, 0777, true); // Create resume folder if it doesn't exist
    }
    
    $resume = $_FILES['resume']['name'];
    $resume_temp = $_FILES['resume']['tmp_name'];
    $resume_extension = pathinfo($resume, PATHINFO_EXTENSION);
    
    // Save resume with the user's email as filename
    $resume_filename = $email . "." . $resume_extension;
    $resume_path = $resume_dir . $resume_filename;

    // Validate file size (1MB limit) and upload the file
    if ($_FILES['resume']['size'] > 1048576) {
        die("File size exceeds 1MB.");
    }

    if (move_uploaded_file($resume_temp, $resume_path)) {
        // Insert user data into the users table
        $sql = "INSERT INTO users (suffix, firstname, middlename, lastname, email, mobile, resume, password)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssss', $suffix, $firstname, $middlename, $lastname, $email, $mobile, $resume_filename, $password);
        
        if ($stmt->execute()) {
            $user_id = $conn->insert_id; // Get the last inserted user ID
            
            // Store the user_id in the session
            $_SESSION['user_id'] = $user_id;

            // Redirect to skills form after registration
            echo("<script>alert('Thank you for registering. Please fill in your skills.')</script>");
            header('Location: skills_form.php');
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        die("Resume upload failed.");
    }
}
?>
