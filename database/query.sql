
CREATE DATABASE IF NOT EXISTS hris_db;
USE hris_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','karyawan') NOT NULL,
    karyawan_id INT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password_hash, role) VALUES ('admin', 'admin_password', 'admin');
INSERT INTO users (username, password_hash, role) VALUES ('user1', 'user1_password', 'karyawan');
INSERT INTO users (username, password_hash, role) VALUES ('user2', 'user2_password', 'karyawan');