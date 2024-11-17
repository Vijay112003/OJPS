<?php
session_start();

// Destroy all session data
session_destroy();
echo "<script>alert('Logout Successfully'); window.location.href = '../index.php';</script>";
exit();
?>
