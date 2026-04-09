# Log Aktivitas - Dokumentasi

## Instalasi

1. **Jalankan SQL untuk membuat tabel:**
   ```bash
   mysql -u root -p peminjaman_ps < add_activity_log_table.sql
   ```
   
   Atau copy-paste isi file `add_activity_log_table.sql` ke phpMyAdmin.

2. **Selesai!** Sistem sudah siap digunakan.

## Fitur

### 1. Log Otomatis
Log akan tercatat **OTOMATIS** saat:
- ✅ User login/logout
- ✅ Tambah user, PS, atau peminjaman
- ✅ Update data
- ✅ Approve/reject peminjaman
- ✅ Proses pengembalian PS

### 2. Halaman Log Aktivitas
- Akses: Admin & Petugas
- URL: `index.php?page=activity_log`
- Fitur filter:
  - Filter by user
  - Filter by aksi (login, create, update, dll)
  - Filter by tanggal (dari-sampai)

### 3. Statistik (Khusus Admin)
- URL: `index.php?page=activity_log&action=stats`
- Menampilkan:
  - Total aktivitas
  - Jumlah per jenis aksi
  - Persentase dalam bentuk progress bar

## Data yang Dicatat

Setiap log menyimpan:
- User ID & nama user
- Jenis aksi (login, create, update, dll)
- Deskripsi detail aktivitas
- IP Address
- User Agent (browser)
- Timestamp

## Akses

- **Admin**: Bisa lihat semua log + statistik
- **Petugas**: Bisa lihat semua log
- **Peminjam**: Tidak ada akses

## Menu Navigasi

Menu "Log Aktivitas" sudah ditambahkan di:
1. Navbar (untuk admin & petugas)
2. Dashboard (card khusus admin & petugas)

## Catatan Penting

⚠️ **TIDAK PERLU INPUT MANUAL!**
Log tercatat otomatis oleh sistem. Halaman log hanya untuk melihat dan memfilter data.

## File yang Ditambahkan

1. `models/ActivityLog.php` - Model
2. `controllers/ActivityLogController.php` - Controller
3. `views/activity_log/index.php` - Halaman daftar log
4. `views/activity_log/stats.php` - Halaman statistik
5. `add_activity_log_table.sql` - Script database

## File yang Dimodifikasi

1. `index.php` - Routing
2. `controllers/AuthController.php` - Log login/logout
3. `controllers/UserController.php` - Log CRUD user
4. `controllers/PSController.php` - Log CRUD PS
5. `controllers/PeminjamanController.php` - Log peminjaman
6. `views/layouts/header.php` - Menu navigasi
7. `views/dashboard.php` - Card log aktivitas
