-- Insert Data PS
-- Jalankan query ini di phpMyAdmin atau MySQL client

USE peminjaman_ps;

-- Hapus data PS lama (opsional, uncomment jika ingin reset)
-- DELETE FROM ps;

-- Insert data PS baru
INSERT INTO ps (nama_ps, tipe, status, harga_per_jam) VALUES
('PS 1', 'PS5', 'tersedia', 15000),
('PS 2', 'PS5', 'tersedia', 15000),
('PS 3', 'PS4', 'tersedia', 10000),
('PS 4', 'PS4', 'tersedia', 10000),
('PS 5', 'PS4', 'tersedia', 10000),
('PS 6', 'PS3', 'tersedia', 8000),
('PS 7', 'PS3', 'tersedia', 8000),
('PS 8', 'PS3', 'tersedia', 8000);

-- Cek hasil insert
SELECT * FROM ps;
