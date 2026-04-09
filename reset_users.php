<?php
// File untuk reset dan insert ulang user default

require_once 'config/Database.php';

echo "<h2>Reset User Default</h2>";

$database = new Database();
$conn = $database->getConnection();

if(!$conn) {
    die("Koneksi database gagal!");
}

try {
    // Hapus semua user
    $query = "DELETE FROM users";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    echo "<p>✓ Semua user berhasil dihapus</p>";
    
    // Hash password
    $password = password_hash('password123', PASSWORD_BCRYPT);
    
    // Insert user default
    $query = "INSERT INTO users (username, password, nama, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    $stmt->execute(['admin', $password, 'Administrator', 'admin']);
    echo "<p>✓ User admin berhasil ditambahkan</p>";
    
    $stmt->execute(['petugas1', $password, 'Petugas Satu', 'petugas']);
    echo "<p>✓ User petugas1 berhasil ditambahkan</p>";
    
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
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
?>

<style>
    body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
    h2 { color: #667eea; }
    p { line-height: 1.6; }
    a { color: #667eea; text-decoration: none; font-weight: bold; }
    a:hover { text-decoration: underline; }
</style>
