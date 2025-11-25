
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

-- Tabel untuk absensi (check-in/check-out)
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    karyawan_id INT NOT NULL,
    check_in DATETIME NOT NULL,
    check_out DATETIME NULL,
    notes TEXT NULL,
    status ENUM('present','late','half_day') DEFAULT 'present',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_attendance_karyawan
        FOREIGN KEY (karyawan_id) REFERENCES karyawan(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_karyawan_date (karyawan_id, check_in)
);

-- Tabel untuk pengajuan cuti (kompatibel dengan fitur karyawan)
CREATE TABLE leave_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    karyawan_id INT NOT NULL,
    leave_type ENUM('annual','sick','emergency','unpaid') NOT NULL DEFAULT 'annual',
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_days INT NOT NULL,
    reason TEXT NOT NULL,
    attachment_file VARCHAR(255) NULL COMMENT 'File PDF/gambar pendukung',
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    approved_by INT NULL COMMENT 'ID user admin yang approve/reject',
    approved_at DATETIME NULL,
    rejection_reason TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_leave_karyawan
        FOREIGN KEY (karyawan_id) REFERENCES karyawan(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_leave_approver
        FOREIGN KEY (approved_by) REFERENCES users(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_karyawan_status (karyawan_id, status),
    INDEX idx_dates (start_date, end_date)
);






