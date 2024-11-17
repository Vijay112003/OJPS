<?php
session_start();
require_once '../utils/db_connect.php';

// Redirect to index if not authenticated
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = '../index.php';</script>";
}

// Handle job submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];
    $job_location = $_POST['job_location'];
    $skills_required = $_POST['skills_required'];
    $salary = $_POST['salary'];
    $posted_by = $_SESSION['user_id'];

    // Insert job into jobs table
    $add_job_sql = "INSERT INTO jobs (job_title, job_description, job_location, skills_required, salary, posted_by) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($add_job_sql);
    $stmt->bind_param('ssssdi', $job_title, $job_description, $job_location, $skills_required, $salary, $posted_by);

    if ($stmt->execute()) {
        echo "<script>alert('Job added successfully.'); window.location.href = 'dashboard.php';</script>";
    } else {
        echo "<script>alert('Error adding job. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Job</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .navbar-custom {
            background-color: #343a40;
        }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link {
            color: white;
        }
        .form-container {
            margin-top: 50px;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="your_listing_jobs.php">Your Job Listings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="eligible_jobs.php">View Eligible Jobs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link btn btn-danger btn-sm text-white" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Job Form -->
<div class="container form-container">
    <h2>Add New Job</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="job_title" class="form-label">Job Title</label>
            <input type="text" class="form-control" id="job_title" name="job_title" required>
        </div>
        <div class="mb-3">
            <label for="job_description" class="form-label">Job Description</label>
            <textarea class="form-control" id="job_description" name="job_description" rows="1" required></textarea>
        </div>
        <div class="mb-3">
            <label for="job_location" class="form-label">Job Location</label>
            <input type="text" class="form-control" id="job_location" name="job_location" required>
        </div>
        <div class="mb-3">
            <label for="skills_required" class="form-label">Skills Required</label>
            <input type="text" class="form-control" id="skills_required" name="skills_required" required>
        </div>
        <div class="mb-3">
            <label for="salary" class="form-label">Salary</label>
            <input type="number" class="form-control" id="salary" name="salary" step="0.01" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Job</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>