-- Create database
CREATE DATABASE IF NOT EXISTS scholasys;
USE scholasys;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','staff') DEFAULT 'staff',
    twofa_secret VARCHAR(255) DEFAULT NULL,
    twofa_enabled BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin (password: admin123)
INSERT INTO users (username, password, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Graduates table
CREATE TABLE graduates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    contact_number VARCHAR(20),
    program VARCHAR(100) NOT NULL,
    graduation_year YEAR NOT NULL,
    survey_token VARCHAR(64) UNIQUE DEFAULT NULL,
    profile_image VARCHAR(255) DEFAULT NULL,
    cv_path VARCHAR(255) DEFAULT NULL,
    show_in_directory BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_program (program),
    INDEX idx_year (graduation_year)
);

-- Employment (current job)
CREATE TABLE employment (
    id INT PRIMARY KEY AUTO_INCREMENT,
    graduate_id INT NOT NULL,
    is_employed BOOLEAN DEFAULT FALSE,
    employer_name VARCHAR(200),
    job_title VARCHAR(150),
    employment_type ENUM('full-time','part-time','self-employed','contractual'),
    monthly_salary DECIMAL(10,2),
    work_location VARCHAR(200),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (graduate_id) REFERENCES graduates(id) ON DELETE CASCADE
);

-- Employment history (multiple jobs)
CREATE TABLE employment_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    graduate_id INT NOT NULL,
    employer VARCHAR(200) NOT NULL,
    job_title VARCHAR(150) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    salary DECIMAL(10,2),
    is_current BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (graduate_id) REFERENCES graduates(id) ON DELETE CASCADE,
    INDEX idx_graduate (graduate_id)
);

-- Tracer surveys
CREATE TABLE tracer_surveys (
    id INT PRIMARY KEY AUTO_INCREMENT,
    graduate_id INT NOT NULL,
    survey_date DATE NOT NULL,
    months_after_graduation INT,
    additional_education TEXT,
    feedback TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (graduate_id) REFERENCES graduates(id) ON DELETE CASCADE
);

-- Survey reminders
CREATE TABLE survey_reminders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    graduate_id INT NOT NULL,
    months_after INT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (graduate_id) REFERENCES graduates(id) ON DELETE CASCADE,
    UNIQUE KEY unique_reminder (graduate_id, months_after)
);

-- Anniversary reminders
CREATE TABLE anniversary_reminders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    graduate_id INT NOT NULL,
    anniversary_year INT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (graduate_id) REFERENCES graduates(id) ON DELETE CASCADE,
    UNIQUE KEY unique_anniversary (graduate_id, anniversary_year)
);

-- Events
CREATE TABLE events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    location VARCHAR(200),
    capacity INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Event registrations
CREATE TABLE event_registrations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    graduate_id INT NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    attended BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (graduate_id) REFERENCES graduates(id) ON DELETE CASCADE,
    UNIQUE KEY unique_registration (event_id, graduate_id)
);

-- Announcements
CREATE TABLE announcements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at DATE DEFAULT NULL
);