-- Create database
CREATE DATABASE IF NOT EXISTS phishguard;
USE phishguard;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    subscription_plan ENUM('free', 'pro', 'enterprise') DEFAULT 'free'
);

-- Insert a default admin user (password: admin123)
INSERT INTO users (username, email, password, full_name, subscription_plan) VALUES
('admin', 'admin@phishguard.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'enterprise')
ON DUPLICATE KEY UPDATE id=id;

-- Create scan_logs table for tracking scans
CREATE TABLE IF NOT EXISTS scan_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    scan_input TEXT,
    scan_result ENUM('safe', 'threat') DEFAULT 'safe',
    detected_signature VARCHAR(255),
    scanned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
