<?php
session_start();
require_once '../utils/db_connect.php';

// Redirect if not authenticated
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = '../index.php';</script>";
}

// Get job ID from query parameter
$job_id = $_GET['job_id'];
$user_id = $_SESSION['user_id'];

// Fetch job details to pre-fill the form
$sql = "SELECT * FROM jobs WHERE id = ? AND posted_by = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $job_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];
    $job_location = $_POST['job_location'];
    $skills_required = $_POST['skills_required'];
    $salary = $_POST['salary'];

    // Update the job details in the database
    $update_sql = "UPDATE jobs SET job_title = ?, job_description = ?, job_location = ?, skills_required = ?, salary = ? WHERE id = ? AND posted_by = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('ssssiii', $job_title, $job_description, $job_location, $skills_required, $salary, $job_id, $user_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Job updated successfully!'); window.location.href = 'your_listing_jobs.php';</script>";
    } else {
        echo "Error: " . $update_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2>Edit Job</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="job_title" class="form-label">Job Title</label>
            <input type="text" class="form-control" id="job_title" name="job_title" value="<?php echo $job['job_title']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="job_description" class="form-label">Job Description</label>
            <textarea class="form-control" id="job_description" name="job_description" rows="4" required><?php echo $job['job_description']; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="job_location" class="form-label">Job Location</label>
            <input type="text" class="form-control" id="job_location" name="job_location" value="<?php echo $job['job_location']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="skills_required" class="form-label">Skills Required</label>
            <input type="text" class="form-control" id="skills_required" name="skills_required" value="<?php echo $job['skills_required']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="salary" class="form-label">Salary</label>
            <input type="text" class="form-control" id="salary" name="salary" value="<?php echo $job['salary']; ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Update Job</button>
    </form>
</div>
</body>
</html>
