<?php
session_start();
require_once '../utils/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];
    $job_location = $_POST['job_location'];
    $skills_required = $_POST['skills_required'];
    $salary = $_POST['salary'];
    $posted_by = $_SESSION['user_id']; // Assuming user_id is stored in session

    // Insert job details into the jobs table
    $sql = "INSERT INTO jobs (job_title, job_description, job_location, skills_required, salary, posted_by, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssss', $job_title, $job_description, $job_location, $skills_required, $salary, $posted_by);

    if ($stmt->execute()) {
        // Redirect to dashboard with success message
        echo "<script>alert('Job added successfully'); window.location.href='dashboard.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    header("Location: add_job.php");
}
?>
