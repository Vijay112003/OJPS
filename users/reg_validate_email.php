<?php
// Include the database connection file
include '../utils/db_connect.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Prepare SQL query to check if the email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the query returned any rows
    if ($result->num_rows > 0) {
        // Email already exists
        echo 'invalid';
    } else {
        // Email does not exist, it's valid for registration
        echo 'valid';
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>