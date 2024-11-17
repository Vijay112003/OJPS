<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Session Time Out'); window.location.href = '../index.php';</script>";
    exit();
}
function getEligibleJobs()
{
    include 'run_knn.php';
    if (isset($_SESSION['filtered_jobs'])) {
        $json = $_SESSION['filtered_jobs'];
        $validJson = str_replace("'", '"', $json);
        $jobs = json_decode($validJson, true);
        return $jobs;

    } else {
        echo "<script>alert('No eligible jobs found.'); //window.location.href = 'dashboard.php';</script>";
        return [];
    }
}
$ejobs = getEligibleJobs();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eligible Jobs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
        }

        .job-listing-container {
            margin-top: 50px;
        }

        .job-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .job-title {
            font-size: 24px;
            font-weight: bold;
        }

        .job-location {
            color: #17a2b8;
        }

        .job-skills {
            color: #dc3545;
        }

        .job-salary {
            color: #28a745;
            font-weight: bold;
        }

        .posted-by {
            color: #6c757d;
        }

        .apply-btn {
            margin-top: 15px;
        }

        .modal-body {
            font-size: 16px;
        }

        #applySuccess {
            font-size: 18px;
            color: green;
            text-align: center;
            margin-top: 10px;
            display: none;
        }

        #celebration {
            display: none;
            text-align: center;
            margin-top: 20px;
            font-size: 24px;
            color: orange;
        }

        .load-btn {
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
    <!-- Page Title -->
    <div class="container mt-4">
        <div class="row">
            <div class="col text-center">
                <h1 class="display-4">Eligible Jobs</h1>
                <p class="lead text-muted">The best jobs that match your skills and preferences</p>
                <hr class="my-4">
            </div>
        </div>
    </div>
    <!-- Eligible Jobs -->
    <div class="container job-listing-container">
        <div class="row">
            <div id="jobs-list" class="col-md-12">
                <?php if (is_array($ejobs)): ?>
                    <div class="row">
                        <?php foreach ($ejobs as $job): ?>
                            <div class="col-md-4 mb-4">
                                <div class="job-card">
                                    <h5 class="job-title"><?php echo htmlspecialchars($job['job_title'] ?? 'N/A'); ?></h5>
                                    <p class="job-description"><?php echo htmlspecialchars($job['job_description'] ?? 'N/A'); ?>
                                    </p>
                                    <p class="job-location"><strong>Location:</strong>
                                        <?php echo htmlspecialchars($job['job_location'] ?? 'N/A'); ?></p>
                                    <p class="job-skills"><strong>Required Skills:</strong>
                                        <?php echo htmlspecialchars($job['skills_required'] ?? 'N/A'); ?></p>
                                    <p class="job-salary"><strong>Salary:</strong>
                                        ₹<?php echo htmlspecialchars($job['salary'] ?? 'N/A'); ?>
                                    </p>
                                    <p class="posted-by"><strong>Posted By:</strong>
                                        <?php echo htmlspecialchars($job['posted_by'] ?? 'N/A'); ?>
                                    </p>
                                    <button class="btn btn-primary apply-btn"
                                        data-id="<?php echo htmlspecialchars($job['id'] ?? ''); ?>"
                                        data-title="<?php echo htmlspecialchars($job['job_title'] ?? ''); ?>"
                                        data-description="<?php echo htmlspecialchars($job['job_description'] ?? ''); ?>"
                                        data-location="<?php echo htmlspecialchars($job['job_location'] ?? ''); ?>"
                                        data-skills="<?php echo htmlspecialchars($job['skills_required'] ?? ''); ?>"
                                        data-salary="<?php echo htmlspecialchars($job['salary'] ?? ''); ?>"
                                        data-postedby="<?php echo htmlspecialchars($job['posted_by'] ?? ''); ?>">
                                        Apply
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning text-center">No eligible jobs found.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Job Apply Modal -->
    <div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="applyModalLabel">Job Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 id="modalJobTitle"></h5>
                    <p id="modalJobDescription"></p>
                    <p><strong>Location:</strong> <span id="modalJobLocation"></span></p>
                    <p><strong>Required Skills:</strong> <span id="modalJobSkills"></span></p>
                    <p><strong>Salary:</strong> $<span id="modalJobSalary"></span></p>
                    <p><strong>Posted By:</strong> <span id="modalPostedBy"></span></p>
                    <button class="btn btn-success" id="confirmApplyBtn">Confirm to Apply</button>
                    <div id="applySuccess">Application Submitted Successfully!</div>
                    <div id="celebration">🎉 Congratulations! 🎉</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        $(document).ready(function () {
            // Bind click event for Apply buttons
            $('.apply-btn').click(function () {
                console.log('Apply button clicked');  // Add this for debugging
                const jobId = $(this).data('id');
                $('#modalJobTitle').text($(this).data('title'));
                $('#modalJobDescription').text($(this).data('description'));
                $('#modalJobLocation').text($(this).data('location'));
                $('#modalJobSkills').text($(this).data('skills'));
                $('#modalJobSalary').text($(this).data('salary'));
                $('#modalPostedBy').text($(this).data('postedby'));

                // Show the modal
                $('#applyModal').modal('show');
            });


            // Apply job logic
            $('#confirmApplyBtn').click(function () {
                $('#applySuccess').fadeIn();
                setTimeout(() => {
                    $('#celebration').fadeIn();
                }, 1000);
            });
        });
    </script>
</body>

</html>