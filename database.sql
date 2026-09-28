-- Student Management System Database
-- Repository: jankovic-student-management-mw1230

CREATE DATABASE IF NOT EXISTS jankovic_student_management
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE jankovic_student_management;

DROP TABLE IF EXISTS students;

CREATE TABLE students (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(30) NOT NULL UNIQUE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    course VARCHAR(150) NOT NULL,
    year_level TINYINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO students (student_id, first_name, last_name, email, course, year_level) VALUES
('23100001', 'Anna', 'Reyes', 'anna.reyes@example.com', 'BS Information Technology', 2),
('23100002', 'James', 'Santos', 'james.santos@example.com', 'BS Computer Science', 3),
('23100003', 'Mika', 'Lim', 'mika.lim@example.com', 'BS Information Systems', 1);
