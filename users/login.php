<?php
    session_start();
    if (isset($_SESSION['user_id'])) {
        echo "<script>window.location.href = '../users/dashboard.php';</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0; /* Remove default margin */
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

        .login-container {
            width: 58.33%; /* 7/12 column size */
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.9); /* Slightly transparent background */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            position: relative; /* To allow stacking above the video */
            z-index: 1; /* Ensure it's above the video */
        }
        
        .login-container h2 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .login-container .form-control {
            margin-bottom: 15px;
        }

        .login-button {
            background-color: #4CAF50;
            color: white;
        }

        .login-button:disabled {
            background-color: grey;
        }
    </style>
</head>
<body>

    <!-- Background Video -->
    <video autoplay muted loop class="video-background">
        <source src="../assets/images/original-e54e32fefb908ac7ff18ded0efb97695.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>

    <div class="container">
        <div class="login-container">
            <h2>Login</h2>
            <form id="loginForm" method="POST" action="process_login.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <small id="emailError" class="text-danger"></small>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required disabled>
                    <small id="passwordError" class="text-danger"></small>
                </div>
                <button type="submit" class="btn login-button w-100" id="loginBtn" disabled>Login</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Get elements
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const loginBtn = document.getElementById('loginBtn');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');

        // Disable password input by default
        passwordInput.disabled = true;

        // Email validation - checks with the server if the email exists
        emailInput.addEventListener('input', function() {
            const email = emailInput.value;

            $.ajax({
                url: 'validate_email.php',
                type: 'POST',
                data: { email: email },
                success: function(response) {
                    if (response === 'valid') {
                        emailError.textContent = '';
                        passwordInput.disabled = false; // Enable password input if email is valid
                    } else {
                        emailError.textContent = 'Email not found';
                        passwordInput.disabled = true; // Disable password input if email is invalid
                    }
                }
            });
        });

        // Password validation - checks with the server if the password is correct
        passwordInput.addEventListener('input', function() {
            const email = emailInput.value;
            const password = passwordInput.value;

            $.ajax({
                url: 'validate_password.php',
                type: 'POST',
                data: { email: email, password: password },
                success: function(response) {
                    if (response === 'valid') {
                        passwordError.textContent = '';
                        loginBtn.disabled = false; // Enable login button if password is correct
                    } else {
                        passwordError.textContent = 'Incorrect password';
                        loginBtn.disabled = true; // Disable login button if password is incorrect
                    }
                }
            });
        });
    </script>

</body>
</html>
