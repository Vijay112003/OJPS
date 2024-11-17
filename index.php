<?php
session_start();
// If the user is already logged in, redirect to the dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: ./users/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Job Portal</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/custom.css"> <!-- Custom styles -->
    <style>
        body {
            margin: 0;
            overflow: hidden; /* Prevent scrollbars */
        }
        .video-background {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -1; /* Place behind other content */
            transform: translate(-50%, -50%);
        }
        .container {
            position: relative; /* To allow the container to stack above the video */
            z-index: 1;
            background-color: rgba(0, 0, 0, 0.5); /* Darker overlay for better text visibility */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
            text-align: center; /* Center align text */
        }
        h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #fff;
        }
        p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            color: #fff;
        }
        .quote {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            font-style: italic;
            color: #fff;
        }
        .btn-custom {
            padding: 15px 30px;
            font-size: 1.2rem;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
        .btn-custom-primary {
            background-color: #00c6ff;
            border: none;
            color: white;
        }
        .btn-custom-primary:hover {
            background-color: #0072ff;
            color: white;
        }
        .btn-custom-secondary {
            background-color: #ffffff;
            border: none;
            color: #0072ff;
        }
        .btn-custom-secondary:hover {
            background-color: #f1f1f4;
            color: #0072ff;
        }
        footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 0.9rem;
            color: #fff;
        }
    </style>
</head>
<body>

    <!-- Background Video -->
    <video autoplay muted loop class="video-background">
        <source src="assets/images/original-e54e32fefb908ac7ff18ded0efb97695.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>

    <div class="container">
        <h1>Welcome to the Online Job Portal</h1>
        <p>Your gateway to finding the best jobs and connecting with top employers.</p>
        <div class="quote">"Opportunities don't happen, you create them." – Chris Grosser</div> <!-- Add your quote here -->
        <a href="users/login.php" class="btn btn-custom btn-custom-primary">Login</a>
        <a href="users/register.php" class="btn btn-custom btn-custom-secondary">Register</a>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Online Job Portal. All rights reserved.
    </footer>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>