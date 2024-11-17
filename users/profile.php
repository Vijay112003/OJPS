<?php
session_start();
require_once '../utils/db_connect.php';

// Redirect to index if not authenticated
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = '../index.php';</script>";
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user_data = $user_result->fetch_assoc();

// Fetch user skills
$skills_sql = "SELECT * FROM user_skills WHERE user_id = ?";
$skills_stmt = $conn->prepare($skills_sql);
$skills_stmt->bind_param('i', $user_id);
$skills_stmt->execute();
$skills_result = $skills_stmt->get_result();

// Handle account deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account'])) {
    // Fetch the resume filename for the user
    $resume_filename = $user_data['resume'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Delete the resume file from the 'resume' folder if it exists
        if (!empty($resume_filename)) {
            $resume_path = "../resume/" . $resume_filename;
            if (file_exists($resume_path)) {
                unlink($resume_path); // Delete the resume file from the server
            }
        }

        // Delete user from users table
        $delete_user_sql = "DELETE FROM users WHERE id = ?";
        $delete_user_stmt = $conn->prepare($delete_user_sql);
        $delete_user_stmt->bind_param('i', $user_id);
        $delete_user_stmt->execute();

        // Delete user skills from user_skills table
        $delete_skills_sql = "DELETE FROM user_skills WHERE user_id = ?";
        $delete_skills_stmt = $conn->prepare($delete_skills_sql);
        $delete_skills_stmt->bind_param('i', $user_id);
        $delete_skills_stmt->execute();

        // Delete jobs posted by the user
        $delete_jobs_sql = "DELETE FROM jobs WHERE posted_by = ?";
        $delete_jobs_stmt = $conn->prepare($delete_jobs_sql);
        $delete_jobs_stmt->bind_param('i', $user_id);
        $delete_jobs_stmt->execute();

        // Commit transaction
        $conn->commit();

        // Destroy session and redirect to homepage after deletion
        session_destroy();
        echo "<script>alert('Account deleted successfully.'); window.location.href = '../index.php';</script>";
        exit;

    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo "Failed to delete account: " . $e->getMessage();
    }
}

// Handle user update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $suffix = $_POST['suffix'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $skills = $_POST['skills']; // Get skills input

    // Update user data
    $update_sql = "UPDATE users SET suffix = ?, firstname = ?, middlename = ?, lastname = ?, email = ?, mobile = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('ssssssi', $suffix, $firstname, $middlename, $lastname, $email, $mobile, $user_id);

    // Begin transaction for update
    $conn->begin_transaction();

    try {
        // Update user details
        $update_stmt->execute();

        // Clear existing skills
        $delete_skills_sql = "DELETE FROM user_skills WHERE user_id = ?";
        $delete_skills_stmt = $conn->prepare($delete_skills_sql);
        $delete_skills_stmt->bind_param('i', $user_id);
        $delete_skills_stmt->execute();

        // Insert new skills
        foreach ($skills as $skill) {
            $insert_skill_sql = "INSERT INTO user_skills (user_id, skill_name) VALUES (?, ?)";
            $insert_skill_stmt = $conn->prepare($insert_skill_sql);
            $insert_skill_stmt->bind_param('is', $user_id, $skill);
            $insert_skill_stmt->execute();
        }

        // Commit transaction
        $conn->commit();

        echo "<script>alert('User details and skills updated successfully.'); window.location.href='dashboard.php';</script>";
        // Refresh user data
        $stmt->execute();
        $user_result = $stmt->get_result();
        $user_data = $user_result->fetch_assoc();
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo "Error updating record: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
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
        .profile-container {
            margin-top: 50px;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .profile-title {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .skill-list {
            list-style-type: none;
            padding: 0;
        }
        .skill-list li {
            background: #e9ecef;
            margin: 5px 0;
            padding: 10px;
            border-radius: 5px;
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
                    <a class="nav-link" href="your_listing_jobs.php">Your Job Listings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="eligible_jobs.php">View Eligible Jobs</a>
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

<div class="container profile-container">
    <h2 class="profile-title">User Profile</h2>
    <h4>Personal Details</h4>
    <p><strong>Full Name:</strong> <?php echo $user_data['suffix'] . ' ' . $user_data['firstname'] . ' ' . $user_data['middlename'] . ' ' . $user_data['lastname']; ?></p>
    <p><strong>Email:</strong> <?php echo $user_data['email']; ?></p>
    <p><strong>Mobile:</strong> <?php echo $user_data['mobile']; ?></p>
    <p><strong>Resume:</strong> <a href="../resume/<?php echo $user_data['resume']; ?>" target="_blank">View Resume</a></p>

    <h4>User Skills</h4>
    <ul class="skill-list">
        <?php if ($skills_result->num_rows > 0): ?>
            <?php while($skill = $skills_result->fetch_assoc()): ?>
                <li><?php echo $skill['skill_name']; ?></li>
            <?php endwhile; ?>
        <?php else: ?>
            <li>No skills found.</li>
        <?php endif; ?>
    </ul>

    <form method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
        <button type="submit" name="delete_account" class="btn btn-danger mt-3">Delete Account</button>
        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#updateModal">Update Details</button>
    </form>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="suffix" class="form-label">Suffix</label>
                        <input type="text" class="form-control" id="suffix" name="suffix" value="<?php echo $user_data['suffix']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="firstname" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $user_data['firstname']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="middlename" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="middlename" name="middlename" value="<?php echo $user_data['middlename']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $user_data['lastname']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $user_data['email']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile Number</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $user_data['mobile']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="skills" class="form-label">Skills (comma separated)</label>
                        <input type="text" class="form-control" id="skills" name="skills[]" value="" placeholder="Enter new skills here">
                    </div>
                    <button type="submit" name="update_user" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
