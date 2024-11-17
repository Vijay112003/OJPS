<?php
session_start();
require_once '../utils/db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Something went wrong Please login again'); window.location.href = '../index.php';</script>";
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the skills, experience, and certification from the form
    $skills = $_POST['skills'];
    $experience_years = $_POST['experience'];
    $certifications = $_POST['certification'];

    if (!empty($skills)) {
        // Prepare SQL query to insert skills into the user_skills table
        $stmt = $conn->prepare("INSERT INTO user_skills (user_id, skill_name, experience_years, certification, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param('isis', $user_id, $skill_name, $experience_years_entry, $certification_entry);

        // Insert each skill into the database
        foreach ($skills as $index => $skill_name) {
            $experience_years_entry = $experience_years[$index];
            $certification_entry = !empty($certifications[$index]) ? $certifications[$index] : null;
            $stmt->execute();
        }

        // Close the statement
        $stmt->close();

        // Redirect to dashboard with a success message
        echo "<script>alert('Skills updated successfully!'); window.location.href = 'dashboard.php';</script>";
    } else {
        echo "<script>alert('No skills added.'); window.location.href = 'skills_form.php';</script>";
    }
}
?>