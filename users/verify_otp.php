<?php
session_start();

if (isset($_POST['otp'])) {
    $user_otp = $_POST['otp'];
    if ($user_otp == $_SESSION['otp']) {
        echo 'valid';
    } else {
        echo 'invalid';
    }
}
?>