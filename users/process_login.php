<?php
session_start();
require_once '../utils/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute query to check if user exists with provided email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Fetch user data
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Store user details in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['firstname'];

            // Success message and redirect to dashboard
            echo "<script>
                    alert('Login successful! Redirecting to dashboard...');
                    window.location.href = 'dashboard.php';
                  </script>";
            exit();
        } else {
            // Incorrect password
            echo "<script>
                    alert('Invalid password. Please try again.');
                    window.location.href = 'login.php';
                  </script>";
            exit();
        }
    } else {
        // User not found
        echo "<script>
                alert('No account found with that email. Please register.');
                window.location.href = 'register.php';
              </script>";
        exit();
    }
} else {
    // If the request method is not POST, redirect back to the login form
    header('Location: login.php');
    exit();
}
?>
