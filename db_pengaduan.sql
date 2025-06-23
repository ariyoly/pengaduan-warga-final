
CREATE DATABASE IF NOT EXISTS db_pengaduan;
USE db_pengaduan;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','warga') NOT NULL
);

CREATE TABLE IF NOT EXISTS laporan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(50) NOT NULL,
    judul VARCHAR(255) NOT NULL,
    isi TEXT NOT NULL,
    status ENUM('baru','proses','selesai') DEFAULT 'baru',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password, role) VALUES
('admin', '$2y$10$9IxUuapEExDo4lP/PL/nWOs3pObRss0epj0IyiU3Y12wbnGxqT36a', 'admin');
-- Password: admin123
