<?php
// File untuk setup awal database dan insert user default

require_once 'config/Database.php';

echo "<h2>Setup Database Peminjaman PS</h2>";

$database = new Database();
$conn = $database->getConnection();

if(!$conn) {
    die("Koneksi database gagal!");
}

echo "<p>✓ Koneksi database berhasil!</p>";

// Cek apakah tabel users sudah ada
try {
    $query = "SELECT COUNT(*) FROM users";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    
    if($count > 0) {
        echo "<p>⚠ Database sudah memiliki {$count} user.</p>";
        echo "<p><a href='reset_users.php'>Klik di sini untuk reset dan insert ulang user default</a></p>";
    } else {
        echo "<p>Database kosong, akan insert user default...</p>";
        insertDefaultUsers($conn);
    }
} catch(PDOException $e) {
    echo "<p>❌ Error: Tabel belum dibuat. Silakan import database.sql terlebih dahulu!</p>";
    echo "<p>Error detail: " . $e->getMessage() . "</p>";
}

// Menambahkan akun default (admin, petugas, peminjam) saat setup awal.
function insertDefaultUsers($conn) {
    // Hash password
    $password = password_hash('password123', PASSWORD_BCRYPT);
    
    try {
        // Insert admin
        $query = "INSERT INTO users (username, password, nama, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute(['admin', $password, 'Administrator', 'admin']);
        echo "<p>✓ User admin berhasil ditambahkan</p>";
        
        // Insert petugas
        $stmt->execute(['petugas1', $password, 'Petugas Satu', 'petugas']);
        echo "<p>✓ User petugas1 berhasil ditambahkan</p>";
        
        // Insert peminjam
        $stmt->execute(['peminjam1', $password, 'Peminjam Satu', 'peminjam']);
        echo "<p>✓ User peminjam1 berhasil ditambahkan</p>";
        
        echo "<hr>";
        echo "<h3>Login Default:</h3>";
        echo "<ul>";
        echo "<li><strong>Admin:</strong> username: admin, password: password123</li>";
        echo "<li><strong>Petugas:</strong> username: petugas1, password: password123</li>";
        echo "<li><strong>Peminjam:</strong> username: peminjam1, password: password123</li>";
        echo "</ul>";
        
        echo "<p><a href='index.php'>Klik di sini untuk login</a></p>";
        
    } catch(PDOException $e) {
        echo "<p>❌ Error insert user: " . $e->getMessage() . "</p>";
    }
}
?>

<style>
    body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
    h2 { color: #667eea; }
    p { line-height: 1.6; }
    a { color: #667eea; text-decoration: none; font-weight: bold; }
    a:hover { text-decoration: underline; }
</style>
