# E-Learning Platform

## Project Overview
This E-Learning Platform is a web-based application developed in PHP with a MySQL database. It supports three roles:
- **Admin**: Manages users, announcements, and courses.
- **Teacher**: Adds and manages courses.
- **Student**: Enrolls in courses, provides feedback, and views announcements.

## Prerequisites
1. **XAMPP** (or any equivalent LAMP/WAMP stack): Install XAMPP for Apache, MySQL, and PHP.
2. **Web Browser**: To access the application.
3. **PHP Version**: Ensure PHP version 7.4 or higher.
4. **Text Editor/IDE**: For editing source code if needed.

## Installation Steps

### 1. Start Apache and MySQL
- Open the XAMPP Control Panel.
- Start both **Apache** and **MySQL** services.

### 2. Set Up the Database
- Access **phpMyAdmin** by navigating to [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
- Run the following SQL queries in the SQL tab to set up the `learning_platform` database and its tables:

```sql
CREATE DATABASE learning_platform;

USE learning_platform;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'teacher', 'admin') NOT NULL
);

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    teacher_id INT NOT NULL,
    FOREIGN KEY (teacher_id) REFERENCES users(id)
);

CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

CREATE TABLE feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    comment TEXT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 3. Place the Project Files
- Download or clone the project repository into the `htdocs` folder of your XAMPP installation (e.g., `C:/xampp/htdocs/`).
- Ensure the folder is named `e-learning-platform`.

### 4. Update Database Configuration
- Open the `config.php` file or ensure database connection details are updated in every file that connects to the database (e.g., `login.php`, `register.php`). Ensure the following:
  ```php
  $conn = new mysqli("localhost", "root", "", "learning_platform");
  ```
- Replace `root` with your MySQL username and the empty string `""` with your MySQL password if applicable.

### 5. Run the Application
- Open your browser and navigate to [http://localhost/e-learning-platform](http://localhost/e-learning-platform).

## Application Features
### Admin Dashboard
- Manage Users: Add, edit, and delete users.
- Manage Courses: View courses added by teachers.
- Manage Announcements: Add, edit, and delete announcements.

### Teacher Dashboard
- Add Courses: Teachers can add and manage their courses.
- Manage Students: View students enrolled in their courses.

### Student Dashboard
- Enroll in Courses: Students can enroll in available courses.
- Feedback: Students can provide feedback on courses.
- Announcements: View latest announcements posted by admins.

## Directory Structure
```
e-learning-platform/
├── index.php
├── login.php
├── register.php
├── dashboard_admin.php
├── dashboard_teacher.php
├── dashboard_student.php
├── add_user.php
├── edit_user.php
├── delete_user.php
├── add_course.php
├── delete_course.php
├── edit_course.php
├── add_announcement.php
├── edit_announcement.php
├── delete_announcement.php
├── styles_admin.css
├── styles_login.css
├── styles_register.css
└── database.sql
```

## Notes
1. Ensure the MySQL database is up and running before accessing the platform.
2. Use strong passwords for admin accounts.
3. Always validate and sanitize user inputs to prevent SQL injection or XSS attacks.
4. Customize styles by editing the respective CSS files (e.g., `styles_admin.css`).

## Troubleshooting
- **Error**: `mysqli_connect_error()` or similar database connection error.
  - Ensure MySQL is running and the database credentials in the code are correct.
- **Page Not Found**: If accessing [http://localhost/e-learning-platform](http://localhost/e-learning-platform).
  - Ensure the project folder is named `e-learning-platform` and is placed in the `htdocs` directory.
- **Foreign Key Constraint Errors**: Occurs when deleting courses or users.
  - Ensure dependent records (e.g., enrollments) are deleted first.

## Security Recommendations
- Use HTTPS to secure data transmission.
- Apply server-side validation to all forms.
- Keep your PHP and MySQL versions up-to-date.
