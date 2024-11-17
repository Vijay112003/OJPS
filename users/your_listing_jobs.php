<?php
session_start();
require_once '../utils/db_connect.php';

// Redirect to index if not authenticated

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = '../index.php';</script>";
}

// Fetch user ID
$user_id = $_SESSION['user_id'];

// Fetch jobs posted by the logged-in user
$jobs_sql = "SELECT * FROM jobs WHERE posted_by = ?";
$stmt = $conn->prepare($jobs_sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$jobs_result = $stmt->get_result();

// Handle job deletion
if (isset($_POST['delete_job'])) {
    $job_id = $_POST['job_id'];

    // Delete the job from jobs table
    $delete_job_sql = "DELETE FROM jobs WHERE id = ? AND posted_by = ?";
    $delete_stmt = $conn->prepare($delete_job_sql);
    $delete_stmt->bind_param('ii', $job_id, $user_id);

    if ($delete_stmt->execute()) {
        echo "<script>alert('Job deleted successfully.'); window.location.href = 'your_job_listings.php';</script>";
    } else {
        echo "<script>alert('Error deleting job. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Job Listings</title>
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
        .job-listing-container {
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
                    <a class="nav-link" href="add_job.php">Add Job</a>
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

<div class="container job-listing-container">
    <h2>Your Job Listings</h2>

    <?php if ($jobs_result->num_rows > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Location</th>
                    <th>Skills Required</th>
                    <th>Salary</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($job = $jobs_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($job['job_title']); ?></td>
                        <td><?php echo htmlspecialchars($job['job_location']); ?></td>
                        <td><?php echo htmlspecialchars($job['skills_required']); ?></td>
                        <td><?php echo htmlspecialchars($job['salary']); ?></td>
                        <td>
                            <a href="edit_job.php?job_id=<?php echo $job['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                            <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this job?');">
                                <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                                <button type="submit" name="delete_job" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No job listings found.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
