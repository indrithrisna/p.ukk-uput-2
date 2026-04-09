# Sistem Peminjaman PS (PlayStation)

Aplikasi web untuk mengelola peminjaman PlayStation menggunakan arsitektur MVC dan OOP native PHP dengan fitur Log Aktivitas.

## Fitur

### Role Admin
- ✅ Kelola User (CRUD)
- ✅ Kelola PS (CRUD)
- ✅ Kelola Peminjaman (View, Approve, Reject, Selesai, Delete)
- ✅ Log Aktivitas & Statistik ✨ BARU

### Role Petugas
- ✅ Kelola PS (CRUD)
- ✅ Kelola Peminjaman (View, Approve, Reject, Selesai, Delete)
- ✅ Log Aktivitas ✨ BARU

### Role Peminjam
- ✅ Ajukan Peminjaman (Create)
- ✅ Lihat Peminjaman Sendiri (Read)
- ✅ Edit Peminjaman (hanya yang status pending)
- ✅ Hapus Peminjaman (hanya yang status pending)

## Fitur Log Aktivitas ✨ BARU

### Aktivitas yang Dicatat
- **Login/Logout**: Setiap user masuk/keluar sistem
- **Create**: Tambah user, PS, atau peminjaman
- **Update**: Edit data user, PS, atau peminjaman
- **Delete**: Hapus data
- **Approve**: Menyetujui peminjaman
- **Reject**: Menolak peminjaman
- **Return**: Proses pengembalian PS

### Informasi yang Tersimpan
- User yang melakukan aktivitas
- Jenis aktivitas (action)
- Deskripsi detail aktivitas
- IP Address
- User Agent (browser/device)
- Timestamp

### Filter & Statistik
- Filter berdasarkan user
- Filter berdasarkan jenis aktivitas
- Filter berdasarkan rentang tanggal
- Statistik aktivitas dengan grafik persentase
- Hanya dapat diakses oleh Admin dan Petugas

## Struktur Folder

```
peminjaman-ps/
├── config/
│   └── Database.php          # Konfigurasi database
├── controllers/
│   ├── AuthController.php    # Controller autentikasi
│   ├── UserController.php    # Controller user
│   ├── PSController.php      # Controller PS
│   ├── PeminjamanController.php # Controller peminjaman
│   └── ActivityLogController.php # Controller log aktivitas ✨ BARU
├── models/
│   ├── User.php              # Model user
│   ├── PS.php                # Model PS
│   ├── Peminjaman.php        # Model peminjaman
│   └── ActivityLog.php       # Model log aktivitas ✨ BARU
├── views/
│   ├── layouts/
│   │   ├── header.php        # Header template
│   │   └── footer.php        # Footer template
│   ├── user/
│   │   ├── index.php         # List user
│   │   ├── create.php        # Form tambah user
│   │   └── edit.php          # Form edit user
│   ├── ps/
│   │   ├── index.php         # List PS
│   │   ├── create.php        # Form tambah PS
│   │   └── edit.php          # Form edit PS
│   ├── peminjaman/
│   │   ├── index.php         # List peminjaman
│   │   ├── create.php        # Form ajukan peminjaman
│   │   ├── edit.php          # Form edit peminjaman
│   │   └── pengembalian.php  # Form pengembalian
│   ├── activity_log/ ✨ BARU
│   │   ├── index.php         # List log aktivitas
│   │   └── stats.php         # Statistik aktivitas
│   ├── login.php             # Halaman login
│   └── dashboard.php         # Halaman dashboard
├── index.php                 # Entry point aplikasi
├── database.sql              # File SQL database lengkap
├── add_activity_log_table.sql # SQL untuk menambah tabel log ✨ BARU
├── fix_user_names.php        # Script perbaikan nama user ✨ BARU
└── README.md                 # Dokumentasi
```

## Instalasi

### 1. Persiapan
- Install XAMPP/WAMP/LAMP
- Pastikan Apache dan MySQL sudah berjalan

### 2. Setup Database

#### Instalasi Baru (Recommended)
```sql
1. Buka phpMyAdmin (http://localhost/phpmyadmin)
2. Import file database.sql
3. Database 'peminjaman_ps' akan otomatis terbuat dengan semua tabel termasuk activity_logs
```

#### Update Database Existing
Jika sudah punya database lama, tambahkan tabel activity_logs:
```sql
1. Buka phpMyAdmin
2. Pilih database 'peminjaman_ps'
3. Import file add_activity_log_table.sql
```

### 3. Perbaiki Nama User (Jika Diperlukan)
Jika nama user menampilkan nama yang salah (misalnya semua "Salsabila"):
```
1. Akses: http://localhost/peminjaman-ps/fix_user_names.php
2. Script akan memperbaiki nama user sesuai default
3. Kembali ke halaman login
```

### 4. Konfigurasi
Edit file `config/Database.php` jika perlu mengubah konfigurasi database:
```php
private $host = "localhost";
private $db_name = "peminjaman_ps";
private $username = "root";
private $password = "";
```

### 5. Jalankan Aplikasi
```
1. Copy folder project ke htdocs (XAMPP) atau www (WAMP)
2. Akses di browser: http://localhost/peminjaman-ps
```

## Login Default

| Username   | Password    | Role     | Nama           |
|------------|-------------|----------|----------------|
| admin      | password123 | Admin    | Administrator  |
| petugas1   | password123 | Petugas  | Petugas Satu   |
| peminjam1  | password123 | Peminjam | Peminjam Satu  |

## Alur Peminjaman

1. **Peminjam** mengajukan peminjaman (status: pending)
   - Log: "Mengajukan peminjaman PS: [nama_ps] untuk [durasi] jam"
2. **Admin/Petugas** menyetujui atau menolak peminjaman
   - Log Approve: "Menyetujui peminjaman ID #[id] oleh [nama_peminjam]"
   - Log Reject: "Menolak peminjaman ID #[id] oleh [nama_peminjam]"
3. Jika disetujui, status berubah menjadi "disetujui"
4. **Admin/Petugas** memproses pengembalian PS
   - Log: "Memproses pengembalian PS ID #[id] kondisi [baik/rusak] [dengan/tanpa denda]"
5. Status berubah menjadi "selesai" dan PS kembali tersedia

## Status Peminjaman

- **Pending**: Menunggu persetujuan
- **Disetujui**: Peminjaman disetujui, PS sedang dipinjam
- **Ditolak**: Peminjaman ditolak
- **Selesai**: Peminjaman selesai, PS sudah dikembalikan

## Status PS

- **Tersedia**: PS dapat dipinjam
- **Dipinjam**: PS sedang dipinjam

## Denda Otomatis

- Jika PS dikembalikan dalam kondisi **rusak**: Denda Rp 5.000 per jam
- Jika PS dikembalikan dalam kondisi **baik**: Tanpa denda
- Perhitungan otomatis: Denda = Rp 5.000 × Durasi Jam

## Teknologi

- PHP Native (OOP)
- MySQL
- PDO (PHP Data Objects)
- MVC Architecture
- Session Management
- Password Hashing (bcrypt)

## Keamanan

- Password di-hash menggunakan bcrypt
- Session-based authentication
- Role-based access control
- SQL Injection prevention (PDO prepared statements)
- XSS prevention (htmlspecialchars)
- Activity logging untuk audit trail

## Fitur Tambahan

- Auto-calculate total harga berdasarkan durasi
- Real-time price calculation
- Responsive design
- User-friendly interface
- Alert notifications
- Confirmation dialogs
- Activity logging dengan IP tracking
- Statistical dashboard untuk admin

## Troubleshooting

### Masalah: Nama user salah/sama semua
**Solusi**: Jalankan `fix_user_names.php`

### Masalah: Error "Table 'activity_logs' doesn't exist"
**Solusi**: Import file `add_activity_log_table.sql`

### Masalah: Log aktivitas tidak tercatat
**Solusi**: 
1. Pastikan tabel activity_logs sudah ada
2. Cek foreign key constraint ke tabel users
3. Pastikan session user_id tersedia

## Lisensi

Free to use for educational purposes.

---

**Dibuat dengan ❤️ menggunakan PHP Native & MySQL**
