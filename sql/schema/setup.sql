CREATE DATABASE IF NOT EXISTS campus_db;
USE campus_db;

-- 1. Buildings & POIs
CREATE TABLE IF NOT EXISTS buildings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    category VARCHAR(50),
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Shuttle Locations
CREATE TABLE IF NOT EXISTS shuttle_locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shuttle_name VARCHAR(50) NOT NULL,
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    speed FLOAT DEFAULT 0.0,
    heading FLOAT DEFAULT 0.0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 3. Notice Board
CREATE TABLE IF NOT EXISTS notices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    priority ENUM('low', 'normal', 'urgent') DEFAULT 'normal',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Study Materials
CREATE TABLE IF NOT EXISTS materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    department VARCHAR(100),
    file_url VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed Sample Buildings
INSERT INTO buildings (name, category, latitude, longitude, description) VALUES
('Senate Building / Admin Block', 'Admin', 8.47990000, 4.54180000, 'Main administrative offices and registry'),
('Faculty of Pure & Applied Sciences', 'Faculty', 8.48120000, 4.54350000, 'Lecture halls, computer labs, and department offices'),
('University Central Library', 'Library', 8.48050000, 4.54220000, '24/7 study areas and e-library resources'),
('Student Union Building (SUB)', 'Services', 8.48280000, 4.54410000, 'Cafeteria, recreation, and student affairs'),
('Campus Main Gate Bus Stop', 'Transport', 8.47800000, 4.54000000, 'Main shuttle terminal and pickup point');

-- Seed Sample Shuttles
INSERT INTO shuttle_locations (shuttle_name, latitude, longitude, speed) VALUES
('Campus Shuttle 01 (Red Line)', 8.47850000, 4.54050000, 25.0),
('Campus Shuttle 02 (Blue Line)', 8.48150000, 4.54300000, 18.5);

-- Seed Sample Notice
INSERT INTO notices (title, content, priority) VALUES
('Orientation Week Schedule', 'Fresh students should report to the Convocation Arena by 9:00 AM.', 'urgent');



DB_HOST=127.0.0.1 DB_PORT=3306 DB_USER=root DB_PASS="090@Golangsavemylife" DB_NAME=campus_db php simulate_gps.php