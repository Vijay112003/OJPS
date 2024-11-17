<?php
session_start();
require_once '../utils/db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Something went wrong Please login again'); window.location.href = '../index.php';</script>";
}

// Get user ID from session
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Skills</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .skills-container {
            width: 50%;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .skills-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #17a2b8;
        }
        .skill-field {
            margin-bottom: 20px;
        }
        .btn-add-skill {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="skills-container">
        <h2>Add Your Skills</h2>
        <form id="skillsForm" method="POST" action="process_skills.php">
            <div id="skillFields">
                <div class="skill-field">
                    <input type="text" class="form-control" name="skills[]" placeholder="Enter skill name" required>
                    <input type="number" class="form-control mt-2" name="experience[]" placeholder="Experience in years" min="0" required>
                    <input type="text" class="form-control mt-2" name="certification[]" placeholder="Certification (if any)">
                </div>
            </div>
            <button type="button" class="btn btn-primary btn-add-skill">Add Another Skill</button>
            <button type="submit" class="btn btn-success w-100">Submit Skills</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Add new skill field when "Add Another Skill" button is clicked
        $('.btn-add-skill').on('click', function () {
            $('#skillFields').append(`
                <div class="skill-field">
                    <input type="text" class="form-control" name="skills[]" placeholder="Enter skill name" required>
                    <input type="number" class="form-control mt-2" name="experience[]" placeholder="Experience in years" min="0" required>
                    <input type="text" class="form-control mt-2" name="certification[]" placeholder="Certification (if any)">
                </div>
            `);
        });
    });
</script>

</body>
</html>
