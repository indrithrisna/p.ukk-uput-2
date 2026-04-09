-- Jalankan di phpMyAdmin > database peminjaman_ps > tab SQL

CREATE TABLE IF NOT EXISTS kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS alat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kategori_id INT NOT NULL,
    nama_alat VARCHAR(100) NOT NULL,
    jumlah INT DEFAULT 1,
    kondisi ENUM('baik', 'rusak', 'perbaikan') DEFAULT 'baik',
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE CASCADE
);

-- Data Kategori
INSERT INTO kategori (nama_kategori, deskripsi) VALUES
('Konsol', 'Perangkat konsol PlayStation'),
('Stik / Controller', 'Controller dan joystick untuk bermain'),
('Headset', 'Headset dan earphone gaming'),
('Kabel', 'Kabel HDMI, power, dan USB'),
('Memory & Storage', 'Memory card dan perangkat penyimpanan'),
('Aksesoris Lainnya', 'Aksesoris tambahan lainnya');

-- Alat: Konsol (id=1)
INSERT INTO alat (kategori_id, nama_alat, jumlah, kondisi, keterangan) VALUES
(1, 'PlayStation 5 Digital Edition', 2, 'baik', 'Unit baru, sudah termasuk kabel power dan HDMI'),
(1, 'PlayStation 5 Disc Edition', 1, 'baik', 'Mendukung game fisik dan digital'),
(1, 'PlayStation 4 Slim', 3, 'baik', '500GB, sudah termasuk 1 stik'),
(1, 'PlayStation 4 Pro', 1, 'baik', '1TB, output 4K'),
(1, 'PlayStation 3 Super Slim', 2, 'baik', '250GB'),
(1, 'PlayStation 3 Slim', 1, 'perbaikan', 'Sedang diperbaiki bagian disc drive');

-- Alat: Stik / Controller (id=2)
INSERT INTO alat (kategori_id, nama_alat, jumlah, kondisi, keterangan) VALUES
(2, 'DualSense PS5 (Putih)', 4, 'baik', 'Controller original PS5'),
(2, 'DualSense PS5 (Hitam)', 2, 'baik', 'Midnight Black edition'),
(2, 'DualShock 4 PS4 (Hitam)', 6, 'baik', 'Controller original PS4'),
(2, 'DualShock 4 PS4 (Merah)', 2, 'baik', 'Magma Red edition'),
(2, 'DualShock 3 PS3', 4, 'baik', 'Controller original PS3'),
(2, 'Stik PS3 Wireless (KW)', 3, 'baik', 'Kompatibel PS3'),
(2, 'Stik PS4 Wireless (KW)', 2, 'rusak', 'Tombol analog kiri macet');

-- Alat: Headset (id=3)
INSERT INTO alat (kategori_id, nama_alat, jumlah, kondisi, keterangan) VALUES
(3, 'Sony Pulse 3D Wireless PS5', 2, 'baik', 'Headset resmi PS5, 3D audio'),
(3, 'Sony Gold Wireless PS4', 2, 'baik', 'Headset resmi PS4'),
(3, 'Headset Gaming Rexus HX20', 3, 'baik', 'Stereo, jack 3.5mm'),
(3, 'Earphone Gaming Fantech EG1', 4, 'baik', 'In-ear, kompatibel semua konsol');

-- Alat: Kabel (id=4)
INSERT INTO alat (kategori_id, nama_alat, jumlah, kondisi, keterangan) VALUES
(4, 'Kabel HDMI 2.1 (2m)', 5, 'baik', 'Mendukung 4K 120fps untuk PS5'),
(4, 'Kabel HDMI 1.4 (1.5m)', 4, 'baik', 'Untuk PS3 dan PS4'),
(4, 'Kabel Power PS5', 3, 'baik', 'Kabel power original PS5'),
(4, 'Kabel Power PS4', 4, 'baik', 'Kabel power original PS4'),
(4, 'Kabel USB-C (charging DualSense)', 5, 'baik', 'Untuk charge controller PS5'),
(4, 'Kabel Micro USB (charging DS4)', 4, 'baik', 'Untuk charge controller PS4');

-- Alat: Memory & Storage (id=5)
INSERT INTO alat (kategori_id, nama_alat, jumlah, kondisi, keterangan) VALUES
(5, 'Memory Card PS3 8GB', 3, 'baik', 'Untuk menyimpan data save game PS3'),
(5, 'SSD Eksternal 1TB', 2, 'baik', 'Ekspansi storage PS4/PS5'),
(5, 'Flash Disk 32GB', 4, 'baik', 'Untuk transfer data dan update sistem');

-- Alat: Aksesoris Lainnya (id=6)
INSERT INTO alat (kategori_id, nama_alat, jumlah, kondisi, keterangan) VALUES
(6, 'Stand PS5 Vertikal', 2, 'baik', 'Stand resmi untuk PS5'),
(6, 'Cooling Fan PS4', 3, 'baik', 'Kipas tambahan untuk mencegah overheat'),
(6, 'Charging Dock DualSense', 2, 'baik', 'Bisa charge 2 controller sekaligus'),
(6, 'Charging Dock DualShock 4', 2, 'baik', 'Bisa charge 2 controller sekaligus'),
(6, 'Cover Silikon Stik PS5', 5, 'baik', 'Pelindung controller dari debu'),
(6, 'Thumb Grip Analog PS4/PS5', 8, 'baik', 'Pelindung analog stick');
