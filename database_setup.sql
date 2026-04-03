-- Database setup for JeromeWorkoutPlan Progress Tracking
CREATE DATABASE IF NOT EXISTS jerome_workout_progress;
USE jerome_workout_progress;

-- Progress entries table
CREATE TABLE IF NOT EXISTS progress_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    weight DECIMAL(5,2) NULL,
    body_fat_percentage DECIMAL(4,2) NULL,
    muscle_mass DECIMAL(5,2) NULL,
    chest_measurement DECIMAL(5,2) NULL,
    waist_measurement DECIMAL(5,2) NULL,
    hip_measurement DECIMAL(5,2) NULL,
    arm_measurement DECIMAL(5,2) NULL,
    thigh_measurement DECIMAL(5,2) NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_date (date)
);

-- Progress photos table
CREATE TABLE IF NOT EXISTS progress_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    progress_id INT NOT NULL,
    photo_path VARCHAR(255) NOT NULL,
    photo_type ENUM('front', 'side', 'back', 'other') DEFAULT 'other',
    caption VARCHAR(255) NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (progress_id) REFERENCES progress_entries(id) ON DELETE CASCADE
);

-- Workout logs table (linking to existing session data)
CREATE TABLE IF NOT EXISTS workout_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    workout_type VARCHAR(100) NOT NULL,
    duration_minutes INT NULL,
    exercises_completed INT DEFAULT 0,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_workout_date (date, workout_type)
);

-- Insert some sample data
INSERT IGNORE INTO progress_entries (date, weight, body_fat_percentage, chest_measurement, waist_measurement, notes) VALUES
('2024-01-01', 75.5, 18.5, 95.0, 85.0, 'Starting measurements'),
('2024-01-08', 74.8, 18.2, 94.5, 84.0, 'Week 1 progress'),
('2024-01-15', 74.2, 17.8, 94.0, 83.0, 'Week 2 progress'),
('2024-01-22', 73.5, 17.5, 93.5, 82.0, 'Week 3 progress'),
('2024-01-29', 73.0, 17.2, 93.0, 81.5, 'Week 4 progress');