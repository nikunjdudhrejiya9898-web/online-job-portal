# 💼 Online Job Recruitment Portal

A web-based **Online Job Recruitment Portal** developed to connect job seekers with employers through a centralized digital platform.

The system allows candidates to create profiles, upload resumes, search and apply for jobs, and track application status. Employers can create company profiles, post job vacancies, view applicants, download resumes, and update application status. An administrator can monitor and manage users, companies, jobs, and overall platform activity.

---

## 📌 Project Overview

The **Online Job Recruitment Portal** is designed to simplify and digitize the traditional recruitment process.

Traditional recruitment methods can be time-consuming and require a lot of manual work. This project provides an online platform where:

* Job seekers can find suitable jobs.
* Candidates can create their profiles.
* Candidates can upload their resumes.
* Employers can post job vacancies.
* Employers can view applicants.
* Employers can download candidate resumes.
* Application status can be updated.
* Admin can manage the complete platform.

The project follows a **3-Tier Architecture** consisting of:

1. Presentation Layer
2. Application/Business Logic Layer
3. Database Layer

---

## 🚀 Main Features

### 👤 Job Seeker Features

* User Registration
* User Login
* Secure Authentication
* User Profile Management
* Add Skills
* Add Experience
* Resume Upload
* Browse Available Jobs
* Search Jobs
* Filter Jobs by Category
* Filter Jobs by Location
* Apply for Jobs
* Track Application Status
* View Pending Applications
* View Accepted Applications
* View Rejected Applications
* Logout

---

### 🏢 Employer / Recruiter Features

* Employer Registration
* Employer Login
* Company Profile
* Recruiter Dashboard
* Post New Job
* Add Job Title
* Add Job Description
* Add Job Location
* Select Job Type
* Select Job Category
* View Posted Jobs
* View Applicants
* Download Candidate Resume
* Review Candidate Applications
* Update Application Status
* Accept Candidate
* Reject Candidate

---

### 🔐 Admin Features

* Admin Login
* Administrator Dashboard
* View Registered Users
* View Companies / Employers
* View Job Listings
* Monitor Platform Activity
* Manage Users
* Manage Job Listings
* Manage Requests
* Monitor Applications

---

## 🛠️ Technologies Used

| Technology         | Purpose                                      |
| ------------------ | -------------------------------------------- |
| HTML5              | Website structure                            |
| CSS3               | Website design and styling                   |
| JavaScript         | Client-side validation and dynamic behaviour |
| PHP                | Backend development                          |
| MySQL              | Database management                          |
| XAMPP              | Local development server                     |
| phpMyAdmin         | MySQL database management                    |
| Visual Studio Code | Code editor                                  |
| Git                | Version control                              |
| GitHub             | Source code hosting                          |

The internship report specifically identifies HTML, CSS, JavaScript, PHP, MySQL, Visual Studio Code, XAMPP and Git as the technologies/tools used in the project.

---

## 🏗️ System Architecture

```text
                  ONLINE JOB PORTAL
                         |
        +----------------+----------------+
        |                |                |
     Frontend          Backend         Database
        |                |                |
 HTML + CSS + JS         PHP             MySQL
        |                |                |
        +----------------+----------------+
                         |
                    Job Portal
```

### 3-Tier Architecture

```text
┌─────────────────────────────────────┐
│         PRESENTATION LAYER          │
│          HTML + CSS + JS            │
│                                     │
│ Login | Register | Dashboard | Jobs │
└──────────────────┬──────────────────┘
                   │
                   ▼
┌─────────────────────────────────────┐
│       APPLICATION / LOGIC LAYER     │
│                PHP                  │
│                                     │
│ Authentication | Job Management     │
│ Profile | Applications | Sessions   │
└──────────────────┬──────────────────┘
                   │
                   ▼
┌─────────────────────────────────────┐
│            DATABASE LAYER            │
│               MySQL                 │
│                                     │
│ Users | Employers | Jobs |          │
│ Applications                        │
└─────────────────────────────────────┘
```

---

# 📊 System Modules

## 1. Guest Module

A guest user can:

* Visit the home page
* View available jobs
* Access login page
* Access registration page
* Search for available opportunities

---

## 2. Job Seeker Module

The Job Seeker module provides functionality for candidates.

```text
Registration
     ↓
Login
     ↓
Create Profile
     ↓
Upload Resume
     ↓
Search Jobs
     ↓
View Job Details
     ↓
Apply for Job
     ↓
Track Application
```

---

## 3. Employer Module

The Employer/Recruiter module allows companies to manage recruitment.

```text
Employer Registration
        ↓
Employer Login
        ↓
Company Profile
        ↓
Post New Job
        ↓
Receive Applications
        ↓
View Applicants
        ↓
Download Resume
        ↓
Accept / Reject Candidate
```

---

## 4. Admin Module

The administrator provides centralized management.

```text
Admin Login
     ↓
Admin Dashboard
     ↓
Manage Users
     ↓
Manage Employers
     ↓
Manage Jobs
     ↓
Monitor Applications
```

---

# 🗄️ Database Structure

The project uses a MySQL relational database named:

```text
job_portal
```

The database contains the main entities required for the recruitment system.

### Main Tables

```text
users
employers / admin
jobs
applications
```

---

## 👤 Users Table

Stores information about job seekers.

Important fields include:

```text
id
name
email
password
phone
skills
experience
resume
created_at
```

---

## 🏢 Admin / Employer Table

Stores administrator/employer information.

Important fields include:

```text
id
company_name
email
password
```

---

## 💼 Jobs Table

Stores job vacancy information.

Important fields include:

```text
id
admin_id
title
description
location
job_type
category
posted_at
```

---

## 📝 Applications Table

Stores job application information.

Important fields include:

```text
id
user_id
job_id
status
applied_at
```

Application status can be:

```text
Pending
Accepted
Rejected
```

---

# 🔗 Database Relationship

```text
              USERS
                |
                | user_id
                |
                ▼
          APPLICATIONS
                ▲
                |
                | job_id
                |
                ▼
               JOBS
                ▲
                |
                | admin_id
                |
                ▼
         ADMIN / EMPLOYER
```

---

# 📈 Data Flow

## Level 0 DFD

The system interacts with four major external entities:

```text
                ┌─────────────┐
                │    ADMIN    │
                └──────┬──────┘
                       │
                       ▼
┌────────────┐   ┌────────────────┐   ┌──────────────┐
│    GUEST   │──▶│   JOB PORTAL   │◀──│  JOB SEEKER  │
└────────────┘   └───────┬────────┘   └──────────────┘
                         ▲
                         │
                  ┌──────┴───────┐
                  │   EMPLOYER   │
                  └──────────────┘
```

---

## Level 1 DFD

The major processes are:

```text
1.0 Authentication
2.0 Profile Management
3.0 Job Management
4.0 Application Management
```

---

# 🔐 Security Features

The project includes security-related functionality such as:

* Login authentication
* Session management
* Password hashing
* Form validation
* Database authentication
* Protected user sessions
* Controlled resume upload
* Role-based access

---

# 📄 Resume Management

Job seekers can upload their resumes through the portal.

Supported resume formats mentioned in the project documentation include:

```text
PDF
DOCX
```

Employers can view applicant information and download candidate resumes.

---

# 🔎 Job Search

The Job Portal provides job searching functionality.

Users can search/filter jobs based on:

```text
Category
Location
```

Example categories:

```text
IT
HR
Sales
Web Development
```

Example job types:

```text
Full Time
Part Time
```

---

# 📊 Application Status

After applying for a job, the candidate can track the application status.

Possible statuses:

```text
Pending
Accepted
Rejected
```

Example flow:

```text
Candidate Applies
       ↓
     Pending
       ↓
Employer Reviews
       ↓
 ┌─────┴─────┐
 ▼           ▼
Accepted   Rejected
```

---

# 🎯 Project Objectives

The main objectives of this project are:

1. To create a user-friendly platform connecting job seekers and hiring companies.
2. To automate the traditional recruitment process.
3. To provide job searching and filtering functionality.
4. To provide secure authentication.
5. To allow candidates to upload resumes.
6. To allow employers to post job vacancies.
7. To allow employers to manage applications.
8. To provide an administrator panel for centralized management.

---

# 📂 Project Structure

A recommended project structure is:

```text
online-job-portal/
│
├── admin/
│   ├── dashboard.php
│   ├── users.php
│   ├── jobs.php
│   └── requests.php
│
├── employer/
│   ├── dashboard.php
│   ├── post_job.php
│   ├── applicants.php
│   └── download_resume.php
│
├── user/
│   ├── dashboard.php
│   ├── profile.php
│   ├── apply.php
│   └── applications.php
│
├── uploads/
│   └── resumes/
│
├── css/
│   └── style.css
│
├── js/
│   └── script.js
│
├── includes/
│   └── db_connect.php
│
├── index.php
├── login.php
├── register.php
├── logout.php
├── jobs.php
│
├── database/
│   └── job_portal.sql
│
└── README.md
```

> Adjust the folder names according to your actual project files before uploading to GitHub.

---

# ⚙️ Installation & Setup

## Step 1: Install XAMPP

Install XAMPP on your computer.

Start:

```text
Apache
MySQL
```

---

## Step 2: Clone / Download Project

Place the project inside the XAMPP `htdocs` directory.

Example:

```text
C:\xampp\htdocs\online-job-portal
```

---

## Step 3: Create Database

Open:

```text
http://localhost/phpmyadmin
```

Create a database:

```text
job_portal
```

---

## Step 4: Import Database

Open the database:

```text
job_portal
```

Then select:

```text
Import
```

Upload:

```text
database/job_portal.sql
```

and click:

```text
Go
```

---

## Step 5: Configure Database Connection

Open:

```text
includes/db_connect.php
```

Configure your MySQL connection.

Example:

```php
<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "job_portal";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>
```

> Change the database credentials according to your local MySQL configuration.

---

# ▶️ Run the Project

After starting Apache and MySQL in XAMPP, open:

```text
http://localhost/online-job-portal/
```

If your folder has a different name, replace `online-job-portal` with your actual folder name.

---

# 🖥️ Project Screenshots

Screenshots of the project can be added below.

## 🏠 Home Page

![Home Page](screenshots/home-page.png)

---

## 🔐 Employer / Recruiter Login

![Employer Login](screenshots/employer-login.png)

---

## 👤 User Profile

![User Profile](screenshots/user-profile.png)

---

## 📋 Application Status

![Application Status](screenshots/application-status.png)

---

## 🔑 Admin Login

![Admin Login](screenshots/admin-login.png)

---

## 📊 Administrator Dashboard

![Admin Dashboard](screenshots/admin-dashboard.png)

---

## 👥 All Users

![All Users](screenshots/all-users.png)

---

## 📩 User Requests

![User Requests](screenshots/user-requests.png)

---

## 💼 New Job

![New Job](screenshots/new-job.png)

---

# 📸 Screenshot Folder Structure

Create a folder named:

```text
screenshots
```

Inside it keep:

```text
screenshots/
│
├── home-page.png
├── employer-login.png
├── user-profile.png
├── application-status.png
├── admin-login.png
├── admin-dashboard.png
├── all-users.png
├── user-requests.png
└── new-job.png
```

The internship documentation contains screenshots/outputs covering the Home Page, Employer/Recruiter Login, User Profile, Application Status, Admin Login, Administrator Dashboard, All Users, User Requests and New Job screens.

---

# 📐 Project Diagrams

The project documentation includes:

* Context Level DFD
* Level 1 DFD
* Level 2 DFD
* ER Diagram
* Use Case Diagram

These can be added to GitHub inside:

```text
docs/
```

Recommended structure:

```text
docs/
│
├── context-dfd.png
├── level-1-dfd.png
├── level-2-dfd.png
├── er-diagram.png
└── use-case-diagram.png
```

---

# 🌟 Advantages

* Reduces manual recruitment work.
* Saves time for candidates and recruiters.
* Centralized job information.
* Easy job searching.
* Online resume management.
* Easy application tracking.
* Employer applicant management.
* Centralized administration.
* User-friendly web interface.

---

# 🔮 Future Enhancements

The current project documentation identifies some features that are not implemented in this version. Future versions could add:

* Online video interviews
* Integrated payment gateway
* Automatic resume parsing
* AI-based resume analysis
* Job recommendation system
* Email notifications
* SMS notifications
* Advanced recruiter analytics
* AI-based candidate matching
* Online interview scheduling
* Real-time notifications
* Mobile application

---

# ⚠️ Current Limitations

The documented version currently has some limitations:

* No integrated video interviewing.
* No online payment gateway for premium employer features.
* No automatic resume parsing.
* Candidates need to manually enter their core skills.

These limitations are described in the project documentation.

---

# 📚 Learning Outcomes

Through this project, the following technical and professional skills were developed:

### Technical Skills

* HTML
* CSS
* JavaScript
* PHP
* MySQL
* Database Management
* Form Validation
* Authentication
* Session Management
* File Upload
* CRUD Operations
* Git and GitHub
* Web Application Development

### Professional Skills

* Problem Solving
* Debugging
* Teamwork
* Communication
* Time Management
* Project Management

The internship documentation describes practical experience with web development, debugging, teamwork, communication and time management.

---

# 👨‍💻 Project Information

**Project:** Online Job Recruitment Portal

**Technology:** PHP, MySQL, HTML, CSS, JavaScript

**Database:** MySQL

**Server:** XAMPP / Apache

**Development Environment:** Visual Studio Code

**Version Control:** Git / GitHub

**Project Type:** Web Application

---

# 🎓 Academic Project

This project was developed as part of an internship/project-based learning program.

**Internship Company:** Amplebrain Technologies

**Location:** Rajkot, Gujarat

**Project:** Online Job Portal

**Internship Duration:** 01 January 2026 – 28 January 2026

---

# 🤝 Contribution

Contributions, suggestions and improvements are welcome.

If you want to contribute:

```text
1. Fork the repository
2. Create a new branch
3. Make your changes
4. Commit your changes
5. Push the branch
6. Create a Pull Request
```

---

# 📜 License

This project is created for educational and academic purposes.

---

# ⭐ Support

If you find this project useful, consider giving the repository a ⭐ on GitHub.

---

## 🔖 Tags

```text
php
mysql
html
css
javascript
job-portal
job-recruitment
online-job-portal
web-development
xampp
full-stack
crud
authentication
resume-upload
```
