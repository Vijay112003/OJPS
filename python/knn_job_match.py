import mysql.connector
from sklearn.neighbors import NearestNeighbors
import numpy as np
import json
import sys
from decimal import Decimal

def fetch_user_skills(user_id):
    try:
        # Connect to MySQL
        connection = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="online_job_portal"
        )
        cursor = connection.cursor()

        # Fetch user's skills from the database
        cursor.execute("SELECT skill_name FROM user_skills WHERE user_id = %s", (user_id,))
        user_skills = [row[0].strip().lower() for row in cursor.fetchall()]  # Normalize user skills

        cursor.close()
        connection.close()
        # print(f"Fetched user skills for user_id {user_id}: {user_skills}")
        return user_skills
    except mysql.connector.Error as err:
        # print(f"Error: {err}")
        return []

def fetch_all_jobs(user_id):
    try:
        connection = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="online_job_portal"
        )
        cursor = connection.cursor()

        # Fetch all jobs from the jobs table, excluding jobs posted by the user
        cursor.execute("SELECT id, job_title, job_description, job_location, skills_required, salary, posted_by FROM jobs WHERE posted_by != %s", (user_id,))
        jobs = cursor.fetchall()

        cursor.close()
        connection.close()
        # print(f"Fetched jobs excluding user_id {user_id}: {jobs}")
        return jobs
    except mysql.connector.Error as err:
        # print(f"Error: {err}")
        return []

def calculate_similarity(user_skills, job_skills):
    # Normalize job skills and compare with user skills
    job_skills_list = [skill.strip().lower() for skill in job_skills.split(',')]  # Normalize job skills
    user_skills = set(user_skills[0].split(','))  # Convert to set for faster lookup
    job_skills_list = set(job_skills_list)  # Convert to set for faster lookup
    # print("User skills:", user_skills)
    # print("Job skills:", job_skills_list)
    common_skills = user_skills.intersection(job_skills_list)
    # print("Common skills:", common_skills)
    return len(common_skills)

def run_knn(user_id):
    # Fetch user skills
    user_skills = fetch_user_skills(user_id)
    if not user_skills:
        # print("No user skills found.")
        return []

    # Fetch all jobs
    jobs = fetch_all_jobs(user_id)
    if not jobs:
        # print("No jobs found.")
        return []

    # Calculate similarities using a simple KNN-like approach
    job_similarity = []
    for job in jobs:
        similarity = calculate_similarity(user_skills, job[4])  # job[4] is the skills_required
        job_similarity.append((job, similarity))
        # print("Job ID:", job[0], "Similarity:", similarity)

    # Sort jobs by similarity (highest similarity first)
    job_similarity.sort(key=lambda x: x[1], reverse=True)

    # Filter jobs with at least one matching skill
    eligible_jobs = [job[0] for job in job_similarity if job[1] >= 1]
    # for job in eligible_jobs:
    #     print("Eligible job ID:", job[0])
    return eligible_jobs

def main(user_id):
    eligible_jobs = run_knn(user_id)
    job_list = []
    for job in eligible_jobs:
        job_list.append({
            "id": job[0],
            "job_title": job[1],
            "job_description": job[2],
            "job_location": job[3],
            "skills_required": job[4],
            "salary": float(job[5]) if isinstance(job[5], Decimal) else job[5],
            "posted_by": job[6]
        })
        # print(f"Job ID: {job[0]}, Job Title: {job[1]}, Job Description: {job[2]}, Job Location: {job[3]}, Skills Required: {job[4]}, Salary: {job[5]}, Posted By: {job[6]}")
    
    # Print job details in JSON format to pass to PHP
    print(job_list)

if __name__ == '__main__':
    if len(sys.argv) != 2:
        sys.exit(1)
    user_id = int(sys.argv[1])
    main(user_id)
