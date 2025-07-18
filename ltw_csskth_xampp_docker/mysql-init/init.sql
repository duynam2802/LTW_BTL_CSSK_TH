-- UTH Health & Fitness Tracking Database

CREATE DATABASE IF NOT EXISTS uth_health;
USE uth_health;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    age INT NULL,
    gender ENUM('male', 'female', 'other') NULL,
    is_active BOOLEAN DEFAULT TRUE,
    remember_token VARCHAR(255) NULL,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Health records table
CREATE TABLE health_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    weight DECIMAL(5,2) NOT NULL,
    height INT NOT NULL,
    systolic_bp INT NULL,
    diastolic_bp INT NULL,
    heart_rate INT NULL,
    measure_date DATE NOT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Nutrition logs table
CREATE TABLE nutrition_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    meal_type ENUM('breakfast', 'lunch', 'dinner', 'snack') NOT NULL,
    food_name VARCHAR(100) NOT NULL,
    quantity INT NOT NULL,
    calories INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Workout logs table
CREATE TABLE workout_logs (
    
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    workout_type VARCHAR(50) NOT NULL,
    duration INT NOT NULL,
    calories_burned INT NOT NULL,
    workout_date DATE NOT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Sleep logs table
CREATE TABLE sleep_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    bedtime TIME NOT NULL,
    wake_time TIME NOT NULL,
    sleep_date DATE NOT NULL,
    quality INT NOT NULL CHECK (quality >= 1 AND quality <= 10),
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Goals table
CREATE TABLE goals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    goal_type VARCHAR(50) NOT NULL,
    target_value DECIMAL(10,2) NOT NULL,
    current_value DECIMAL(10,2) DEFAULT 0,
    unit VARCHAR(20) NOT NULL,
    target_date DATE NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert sample data
INSERT INTO users (full_name, email, password, age, gender) VALUES
('Admin', 'admin@uth.edu.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 22, 'male');

-- Dữ liệu health records cho 7 ngày
INSERT INTO health_records (user_id, weight, height, systolic_bp, diastolic_bp, heart_rate, measure_date, notes) VALUES
-- Nguyễn Văn An (7 ngày)
(1, 70.0, 175, 120, 80, 72, DATE_SUB(CURDATE(), INTERVAL 6 DAY), 'Cảm thấy khỏe mạnh'),
(1, 69.8, 175, 118, 78, 70, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'Tập thể dục đều đặn'),
(1, 69.5, 175, 122, 82, 75, DATE_SUB(CURDATE(), INTERVAL 4 DAY), 'Hơi mệt do thiếu ngủ'),
(1, 69.2, 175, 119, 79, 73, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'Sức khỏe ổn định'),
(1, 68.9, 175, 121, 81, 71, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Cảm thấy nhẹ nhõm'),
(1, 68.7, 175, 117, 77, 69, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Tập luyện hiệu quả'),
(1, 68.5, 175, 120, 80, 72, CURDATE(), 'Mục tiêu giảm cân đạt được');

-- Create indexes for better performance
CREATE INDEX idx_health_user_date ON health_records(user_id, measure_date);
CREATE INDEX idx_nutrition_user_date ON nutrition_logs(user_id, created_at);
CREATE INDEX idx_workout_user_date ON workout_logs(user_id, workout_date);
CREATE INDEX idx_sleep_user_date ON sleep_logs(user_id, sleep_date);