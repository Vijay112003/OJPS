<?php
session_start();
require_once '../utils/db_connect.php';

// Redirect if not authenticated
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Something went wrong Please login again'); window.location.href = '../index.php';</script>";
}

// Get job ID from query parameter
$job_id = $_GET['job_id'];
$user_id = $_SESSION['user_id'];

// Delete the job from the database
$sql = "DELETE FROM jobs WHERE id = ? AND posted_by = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $job_id, $user_id);

if ($stmt->execute()) {
    echo "<script>alert('Job deleted successfully!'); window.location.href = 'dashboard.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}
?>
