<?php
if (isset($_SESSION['user_id'])) {
    $userId = escapeshellarg($_SESSION['user_id']); // Escape the user_id for security
    $command = escapeshellcmd("python ../python/knn_job_match.py $userId");
    $_SESSION['filtered_jobs'] = shell_exec($command);
    if (isset($_SESSION['filtered_jobs'])) {
    } else {
        echo'<script>alert("No eligible jobs for you"); window.location.href = "../index.php";</script>';
    }
} else {
    echo'<script>alert("Session Time Out"); window.location.href = "../index.php";</script>';
}
