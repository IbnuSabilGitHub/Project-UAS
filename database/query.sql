
CREATE DATABASE IF NOT EXISTS hris_db;
USE hris_db;


CREATE TABLE karyawan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nik VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(50) NULL,
    position VARCHAR(100) NULL,
    join_date DATE NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    employment_status ENUM('active','resigned','terminated') NOT NULL DEFAULT 'active'
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('super_admin','admin','karyawan') NOT NULL,
    karyawan_id INT NULL,
    status ENUM('active','disabled','locked') NOT NULL DEFAULT 'active',
    must_change_password TINYINT(1) NOT NULL DEFAULT 0,
    password_last_changed DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_users_karyawan_id (karyawan_id),
    CONSTRAINT fk_users_karyawan
        FOREIGN KEY (karyawan_id) REFERENCES karyawan(id)
        ON DELETE SET NULL ON UPDATE CASCADE
);



