<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online_job_portal";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === FALSE) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Create users table if it doesn't exist
$users_table = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    suffix VARCHAR(10),
    firstname VARCHAR(50) NOT NULL,
    middlename VARCHAR(50),
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mobile VARCHAR(10) NOT NULL,
    resume VARCHAR(255), -- Store the resume file path
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($users_table) === FALSE) {
    die("Error creating users table: " . $conn->error);
}

// Create jobs table if it doesn't exist
$jobs_table = "CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_title VARCHAR(100) NOT NULL,
    job_description TEXT NOT NULL,
    job_location VARCHAR(100),
    skills_required TEXT NOT NULL,
    salary DECIMAL(10, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    posted_by INT,
    FOREIGN KEY (posted_by) REFERENCES users(id) ON DELETE CASCADE
)";
if ($conn->query($jobs_table) === FALSE) {
    die("Error creating jobs table: " . $conn->error);
}

// Create user_skills table if it doesn't exist
$user_skills_table = "CREATE TABLE IF NOT EXISTS user_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    skill_name VARCHAR(100) NOT NULL,
    experience_years INT,
    certification TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
if ($conn->query($user_skills_table) === FALSE) {
    die("Error creating user_skills table: " . $conn->error);
}

?>
