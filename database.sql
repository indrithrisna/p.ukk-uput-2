-- Database: peminjaman_ps

CREATE DATABASE IF NOT EXISTS peminjaman_ps;
USE peminjaman_ps;

-- Tabel users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    role ENUM('admin', 'petugas', 'peminjam') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel ps
CREATE TABLE ps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_ps VARCHAR(50) NOT NULL,
    tipe ENUM('PS3', 'PS4', 'PS5') NOT NULL,
    status ENUM('tersedia', 'dipinjam') DEFAULT 'tersedia',
    harga_per_jam INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel peminjaman
CREATE TABLE peminjaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    ps_id INT NOT NULL,
    tanggal_pinjam DATETIME NOT NULL,
    tanggal_kembali DATETIME NULL,
    kondisi_ps ENUM('baik', 'rusak') NULL,
    denda INT DEFAULT 0,
    keterangan TEXT NULL,
    durasi_jam INT NOT NULL,
    total_harga INT NOT NULL,
    status ENUM('pending', 'disetujui', 'ditolak', 'selesai') DEFAULT 'pending',
    approved_by INT NULL,
    approved_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel activity_logs
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
);

-- Insert data default
-- Password untuk semua user: password123

INSERT INTO users (username, password, nama, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin'),
('petugas1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Petugas Satu', 'petugas'),
('peminjam1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Peminjam Satu', 'peminjam');

INSERT INTO ps (nama_ps, tipe, status, harga_per_jam) VALUES
('PS 1', 'PS5', 'tersedia', 15000),
('PS 2', 'PS5', 'tersedia', 15000),
('PS 3', 'PS4', 'tersedia', 10000),
('PS 4', 'PS4', 'tersedia', 10000),
('PS 5', 'PS4', 'tersedia', 10000),
('PS 6', 'PS3', 'tersedia', 8000),
('PS 7', 'PS3', 'tersedia', 8000),
('PS 8', 'PS3', 'tersedia', 8000);
